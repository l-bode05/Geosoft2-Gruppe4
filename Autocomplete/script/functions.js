//JavaScript Functions

/**
 * 
 * @returns autosuggest values in autosuggest-container as list
 */

function autoSuggest()
{
    
    var autoSuggestVal = $('#autosuggest').val();
    
    if(autoSuggestVal != '')
    {
        
        $.ajax({
            url: 'php/ajax/autosuggest.php?query='+autoSuggestVal,
            success: function(result)
            {
                $('#autosuggest-container').html(result);
            }
        });
    }
}