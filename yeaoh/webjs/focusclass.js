$(document).ready(
    function()
    {
        $('div').hover
        (
            function()
            {
            $(this).attr('id','idtest');
            $(this).text('This element id is'+$(this).attr('id'));
            },
        function()
        {
            $(this).attr('id','');
            $(this).text("Has been changed!");    
        }
        );
    }
);