
<script>
    $(document).ready(function(){
        $("#logoutlink").click(function(event){
            $.post('php/return/ret_logout.php', $(this).serialize(), function(data){
                setTimeout(function () { location.reload(true); }, 10);
            });
         });
     });
</script>


<li>
    <a href="#" id="logoutlink"><b>Logout</b></a>
</li>