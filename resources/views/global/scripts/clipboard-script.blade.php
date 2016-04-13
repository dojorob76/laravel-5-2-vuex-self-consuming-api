<!--
TO USE: Add 'clipboard' class to button and 'clipboard-result' class for feedback. Use data-attributes for target.

EXAMPLE:
<input id="text-to-copy" value="This text will be copied to clipboard">
<button class="btn btn-primary clipboard" data-clipboard-target="#text-to-copy">
<label class="label label-info clipboard-result"></label>

See: https://zenorocha.github.io/clipboard.js/ for more info
 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.8/clipboard.min.js"></script>

<script>
    var clipboard = new Clipboard('.clipboard');

    clipboard.on('success', function(e){
        $('.clipboard-result').text('Copied to Clipboard!');
        e.clearSelection();
    });

    clipboard.on('error', function(e){
        $('.clipboard-result').text('Press Ctrl+C on a PC or Cmnd+C on a Mac to copy');
    });
</script>