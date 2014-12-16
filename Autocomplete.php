<html>




	<head>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#input").keyup(function(){
                            var input = $("#input").val();
                            input = trim(input);
                            if (input)
                            {
                            $.ajax({
                                    url: "ajax.php",
                                    data: "input="+input,
                                    success: function(msg) {
                                        $("#suggest").html(msg);
                                        $("#suggest ul li").mouseover(function()(
                                                $("#suggest ul li").removeClass("hover");
                                                $(this).addClass("hover");
                                                ))                                 
                                
                                        $("#suggest ul li").click(function()(
                                                var value = $(this).html();
                                                $("#input").val(value);
                                                $("#suggest ul").remove();
                                                        ));
                                                )
                                    } 
                                });
                            }
                        });
                    });
                </script>
        </head>
        <body>
            <div id="box" >
                <input type="text" name="input" id="input" >
                <input type="submit" value="Search" class="submit" /> 
                <div id="suggest"></div>
            </div>
        </body>
</html>
