<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class QzTraySetup extends Command
{
    protected $signature = 'qz:setup';
    protected $description = 'Generate a CA + site certificate chain for QZ Tray silent printing';

    public function handle()
    {
        $storageDir = storage_path('app/qz-tray');

        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        // Find OpenSSL config (required on Windows/XAMPP)
        $opensslConf = null;
        $possiblePaths = [
            'C:\\xampp\\apache\\conf\\openssl.cnf',
            'C:\\xampp\\php\\extras\\openssl\\openssl.cnf',
            'C:\\xampp\\php\\extras\\ssl\\openssl.cnf',
        ];
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $opensslConf = $path;
                break;
            }
        }

        $keyConfig = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        if ($opensslConf) {
            $keyConfig['config'] = $opensslConf;
            $this->info("Using OpenSSL config: {$opensslConf}");
        }

        $this->info('Generating Root CA...');

        // ============================
        // Step 1: Create Root CA
        // ============================
        $caKey = openssl_pkey_new($keyConfig);
        if (!$caKey) {
            $this->error('Failed to generate CA key: ' . openssl_error_string());
            return 1;
        }

        $caDn = [
            'commonName' => 'RPRO Plus POS Root CA',
            'organizationName' => 'RPRO Plus',
            'countryName' => 'MM',
        ];

        $caCsr = openssl_csr_new($caDn, $caKey, $keyConfig);
        $caCert = openssl_csr_sign($caCsr, null, $caKey, 3650, $keyConfig); // Self-signed, 10 years

        // Export Root CA certificate
        openssl_x509_export($caCert, $caCertPem);
        $caPath = $storageDir . '/ca-certificate.crt';
        file_put_contents($caPath, $caCertPem);

        $this->info('✅ Root CA generated.');

        // ============================
        // Step 2: Create Site Certificate (signed by Root CA)
        // ============================
        $this->info('Generating Site Certificate...');

        $siteKey = openssl_pkey_new($keyConfig);
        if (!$siteKey) {
            $this->error('Failed to generate site key: ' . openssl_error_string());
            return 1;
        }

        $siteDn = [
            'commonName' => 'RPRO Plus POS',
            'organizationName' => 'RPRO Plus',
            'countryName' => 'MM',
        ];

        $siteCsr = openssl_csr_new($siteDn, $siteKey, $keyConfig);
        // Sign with CA key (not self-signed!)
        $siteCert = openssl_csr_sign($siteCsr, $caCert, $caKey, 3650, $keyConfig);

        // Export site certificate
        openssl_x509_export($siteCert, $siteCertPem);
        $certPath = $storageDir . '/digital-certificate.txt';
        file_put_contents($certPath, $siteCertPem);

        // Export site private key
        openssl_pkey_export($siteKey, $siteKeyPem, null, $keyConfig);
        $keyPath = $storageDir . '/private-key.pem';
        file_put_contents($keyPath, $siteKeyPem);

        $this->info('✅ Site certificate generated.');
        $this->info("   CA cert:     {$caPath}");
        $this->info("   Site cert:   {$certPath}");
        $this->info("   Private key: {$keyPath}");

        // ============================
        // Step 3: Copy CA cert as override.crt to QZ Tray
        // ============================
        $qzTrayDir = 'C:\\Program Files\\QZ Tray';
        $overridePath = $qzTrayDir . '\\override.crt';

        if (is_dir($qzTrayDir)) {
            if (@copy($caPath, $overridePath)) {
                $this->info("✅ Root CA copied to: {$overridePath}");
            } else {
                $this->warn("Could not copy to {$overridePath} (needs admin).");
                $this->info("Run as admin or manually copy:");
                $this->info("  FROM: {$caPath}");
                $this->info("  TO:   {$overridePath}");
            }
        } else {
            $this->warn("QZ Tray not found at {$qzTrayDir}.");
            $this->info("Manually copy {$caPath} to your QZ Tray folder as 'override.crt'");
        }

        $this->newLine();
        $this->warn('⚠️  Restart QZ Tray for changes to take effect!');
        $this->info('   Right-click QZ Tray icon in system tray → Exit, then reopen.');
        return 0;
    }
}
