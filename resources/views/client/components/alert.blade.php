
@if (session('success'))
    <script>
        const VDmessage = new VdMessage();
        VDmessage.show("success", "{{ session('success') }}")
    </script>
@endif

@if (session('error'))
    <script>
        const VDmessage = new VdMessage();
        VDmessage.show("error", "{{ session('error') }}")
    </script>
@endif

@if (session('warning'))
    <script>
        const VDmessage = new VdMessage();
         VDmessage.show("warning", "{{ session('warning') }}")
    </script>
@endif

@if (session('info'))
    <script>
        const VDmessage = new VdMessage();
        VDmessage.show("info", "{{ session('info') }}")
    </script>
@endif


