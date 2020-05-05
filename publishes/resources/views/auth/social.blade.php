<script>
    @if(isset($success))
        window.opener.history.back();
    @else
        window.opener.socialLoginFail();
    @endif
    window.close();
</script>