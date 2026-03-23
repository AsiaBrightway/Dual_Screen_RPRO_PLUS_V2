@extends('layouts.admin.master')
@section('title', 'Asia Brightway')

@section('content')
    <section class="home-section"
        style="background-image: url('{{ asset('img/rpro_bg3.jpg') }}'); background-size: cover; background-position: center;">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text" style="font-size: 16px">Powered by <a href="http://asiabrightway.com/" target="_blank"
                    style="font-size: 12px">Asia
                    Brightway IT.</a>
            </span>
        </div>
        <div class="home-content">
            <span style="position: fixed; bottom:10px; right:15px; color:#6f44d1; font-weight:bold">
                <i class='bx bx-phone-call' style="margin-right: 5px"></i>09-256400425
            </span>
        </div>
    </section>
    <script src="{{ asset('script/links_js/jquery.3.7.1.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.1.11.1.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/company_script.js') }}"></script>
    <script>
    // 1. Standard Number Test
    async function testCOM2(baudRate) {
        const statusDiv = document.getElementById('status');

        if (!("serial" in navigator)) return alert("Use Chrome!");

        try {
            statusDiv.innerText = "Connecting...";
            const port = await navigator.serial.requestPort();
            await port.open({ baudRate: baudRate });

            const writer = port.writable.getWriter();
            const encoder = new TextEncoder();

            // We send the number TWICE just to be sure
            // Some screens miss the first character
            // 'Q' and 'A' are sometimes used as commands, but raw numbers are safest first.
            const data = encoder.encode("99999.00\r\n");

            await writer.write(data);

            writer.releaseLock();
            await port.close();

            statusDiv.innerText = `Sent "12345.00" at ${baudRate} speed. Check screen.`;

        } catch (err) {
            alert("Error: " + err.message);
        }
    }

    // 2. Wake Up / Initialize Test
    async function wakeUpScreen() {
        try {
            const port = await navigator.serial.requestPort();
            await port.open({ baudRate: 2400 }); // Try 2400 first for wake up
            const writer = port.writable.getWriter();
            const encoder = new TextEncoder();

            // COMMAND: ESC @ (Initialize) + ESC Q A (Send Data Command) + Number
            // This is the standard "EPSON" command set used by many LED screens
            const initCmd = new Uint8Array([0x1B, 0x40]);
            const showCmd = encoder.encode("\x1BQA12345.00\r");

            await writer.write(initCmd); // Wake up
            await writer.write(showCmd); // Show number

            writer.releaseLock();
            await port.close();

            document.getElementById('status').innerText = "Sent Wake Up Command.";
        } catch(e) { alert(e); }
    }
</script>
@endsection
