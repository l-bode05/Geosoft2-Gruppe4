/* 
 * Element functions
 * 
 * Contains javascript/jquery functions to handle elements
 */


/* TextboxSearch
 * 
 * Invoked when clicked on "search" button at the top
 * Switches to filter tab and fills textfield
 */
function TextboxSearch() {
    ShowTab("panel3");
    $('#keyword').val($('#tbs_keyword').val());
    $('#filter_form').submit();
}

/* FillAnswerForm
 * 
 * Invoked: Click on 'edit' comment button
 * Fills a existing answer form with given values
 */
function FillAnswerForm(title, content, resources) {
     $('#ans_title').val(title);     
     $('#ans_comment').val(content);
     $('#ans_resources').val(resources);
     $('#userdiv').html("");
}

/* ShowUserProfile
 * 
 * Invoked: Click on a user profile link
 * Switches to hidden tab, load userdatas and show them
 * 
 * @sources: ret_getuserprofile.php
 */
function ShowUserProfile(id) {
    ShowTab("panel_showcomment");
     
    // Submit Post Request
    $.post('php/return/ret_getuserprofile.php', "id="+id, function(data){
            $('#showncomments').html(data);
    });
}

/* ShowTab
* 
* Deactivates current tab and switch to given one
* 
* @param: panel_name name of panel to switch to(panel1..3)
*/  
function ShowTab(panel_name) {
     // Show only hidden "Show Comment" tab
     var y = document.getElementsByClassName('content active'); 
     if(y.length>0) y[0].className="content";
     document.getElementById('panel1').className="content";
     document.getElementById('panel2').className="content";
     document.getElementById('panel3').className="content";

     document.getElementById(panel_name).className="content active";
     var y = document.getElementsByClassName('active');
     y[0].className="false";
 } 
 
 
/* ShowEditForm
 * 
 * Invoked: Click on a comment 'edit' link
 * Switches to hidden tab, displays the editform
 * 
 * @sources: ret_editform.php
 */ 
function ShowEditForm(id) {
    ShowTab("panel_showcomment");
     
    // Submit Post Request
    $.post('php/return/ret_editform.php', "id="+id, function(data){
            $('#showncomments').html(data);
    });
     
 }
 
 /*RateGeodata
 * 
 * Sends a rating 
 * Invoked: Submit a rating form
 * 
 * @param: geodataid 
 * @sources: ret_rategeodata.php
 */ 
 function RateGeodata(geodataid) {
    var rating = $('#rateit-range').rateit('value');
    
    $('#ratingdiv').html("Thanks for rating");
    
     // Submit Post Request
    $.post('php/return/ret_rategeodata.php', "geodataid="+geodataid+"&rating="+rating, function(data){
        
    });
 }
 
 
 /*ShowCommentsToTag
 * 
 * Invoked: Click on a tag
 * 
 * @param: tag string  
 */ 
function ShowCommentsToTag(tag) {
     ShowTab("panel_showcomment");
     // Insert Content
     InsertCommentTagDatas(tag);
 } 
 

/* InsertCommentTagDatas
* 
* Displays marker with given tag
* 
* @source: ret_getfirstcomments.php
*/ 
function InsertCommentTagDatas(tag) {

    $('#showncomments').html("Loading..");
    // Submit Post Request
    $.post('php/return/ret_getfirstcomments.php', "tag="+tag, function(data){
            $('#showncomments').html(data.split("°")[0]);
            ShowMarkersFromJson(data.split("°")[1]);
    });

    $('#answer_to').val("commentid");

}

/* InsertCommentDatas
* 
* Displays the comment thread to a given id, moves to position
* 
* @param: commentid Id of searched comment
* @source: ret_getcommentthread.php
*/ 
function InsertCommentDatas(commentid) {
    $('#showncomments').html("Loading..");
    // Submit Post Request
    $.post('php/return/ret_getcommentthread.php', "id="+commentid, function(data){
        
            $('#showncomments').html(data.split("°")[0]);
            var objs = jQuery.parseJSON(data.split("°")[1]);    
            ShowPosition(objs[0]["positionx"], objs[0]["positiony"]);
            ShowSpaceExp(data.split("°")[2]);
            
    });

    $('#answer_to').val("commentid");
 if(spaceexp_layer!=null) map.removeLayer(spaceexp_layer);
}



/* ShowComment
* 
* Shows hidden tab and insert the comment thread datas
* 
* @param: commentid Id of searched comment
*/  
function ShowComment(commentid) {
     ShowTab("panel_showcomment");
     // Insert Content
     InsertCommentDatas(commentid);
 } 
 
 
 

 
 
     
// Always Hide Elements, which are not shown
$(document).click(function(e) {
   if(document.getElementById('panel1').className!=="content" || 
       document.getElementById('panel2').className!=="content" ||
               document.getElementById('panel3').className!=="content") {
                   document.getElementById('panel_showcomment').className="content";
               };
});