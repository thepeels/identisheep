<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help extends Model {

	public static $help_text =
        [
        'addewe'    =>  ['Help for Add a Ewe/Tup page',
            '<p>On this page alter the entry date if necessary.<br>Enter the tag number if a home bred ewe,
            but also the flock number if it is a bought in sheep<br> The tag colour is optional but does appear as a column
            in many of the lists.</p>'],
        'addtup'    =>  ['Help for Add a Ewe/Tup page',
            '<p>On this page alter the entry date if necessary.<br>Enter the tag number if a home bred ewe,
            but also the flock number if it is a bought in sheep<br> The tag colour is optional but does appear as a column
            in many of the lists.</p>'],
        'seek'      =>  ['Help for Sheep finder page',
            '<p>Flock Number entered must be six digits - i.e. excluding the \'UK\' and the leading 
            zero.<br>The Tag number can be entered without the leading zeros but will always be shown with 5 digits.</p>'],
        'search'    =>  ['Help for Search page',
            '<p>The Tag number can be entered without the leading zeros but will always be returned with 5 digits. <br>  
             The search includes sheep in the flock, dead sheep, and sheep moved off the holding.</p>'],

        'batchops'  =>  ['.csv File entry',
            '<p>First prepare your .csv File. This can be from a tag reader, or a file can be generated from 
             a market list of tag reads. <br> To do this latter, scan the document with \'OCR\' and save it as a text 
             (.txt) file. Then open the file in e.g. notepad. <br> To load correctly the file must have NO 
             lines above the tag list, so adjust this accordingly. <br> Each line must contain all 
             the tag data, and is followed by a line break<br>like this:- <br>826 012312300051<br>826 012312300151<br>or this:-<br>UK012312300053<br> UK012312300088
             <br>Extra spaces will cause a mis-read, and there is no final comma on the line, it will cause a \'sheep\' 
             with all zeros to be loaded. </p>
             <p>Excel Files can also be loaded, the tag numbers need to be in a column with \'EID\' in the first cell. 
             If you are using a non-microsoft spreadsheet programme, save the file in Excel 97/2000/XP format and it 
             should work. Any problems send us a message, and we\'ll try to rectify them.</p>
             The default date is today, change this if you want to.<br>Enter the holding number and/or name 
             of the destination for the sheep.<br>Select your previously prepared and saved file, and use the 
             \'check\' button to generate a list <br>which you can use to see whether your file is being read 
             correctly.</p>
             If you are satisfied with the list, press the backspace button and then the \'load\' button.<br> Now the 
             list will be loaded into your \'off list\' (Sheep Off Holding) and also removed from the \'all sheep\' 
             list.<br>'],
        'batchopson'  =>  ['.csv File entry',
            '<p>First prepare your .csv File. This can be from a tag reader, or a file can be generated from 
             a market list of tag reads. <br> To do this latter, scan the document with \'OCR\' and save it as a text 
             (.txt) file. Then open the file in e.g. notepad. <br> To load correctly the file must have NO 
             lines above the tag list, so adjust this accordingly. <br> Each line must contain all 
             the tag data, and is followed by a line break<br>like this:- <br>826 012312300051<br>826 012312300151<br>or this:-<br>UK012312300053<br> UK012312300088
             <br>Extra spaces will cause a mis-read, and there is no final comma on the line, it will cause a \'sheep\' 
             with all zeros to be loaded. </p>
             <p>Excel Files can also be loaded, the tag numbers need to be in a column with \'EID\' in the first cell. 
             If you are using a non-microsoft spreadsheet programme, save the file in Excel 97/2000/XP format and it 
             should work. Any problems send us a message, and we\'ll try to rectify them.</p>
             The default date is today, change this if you want to.<br>Enter the holding number and/or name 
             of the destination for the sheep.<br>Select your previously prepared and saved file, and use the 
             \'check\' button to generate a list <br>which you can use to see whether your file is being read 
             correctly.</p>
             If you are satisfied with the list, press the backspace button and then the \'load\' button.<br> Now the 
             list will be loaded into your Sheep List -> Ewes.<br>'],
        'batch'     =>  ['Batch of Sheep onto Holding',',<p>Change the default date if relevant.<br> 
            Enter the details of a batch of sheep where the tag series is continuous with no gaps. <br> 
            If you have a gap, go up to the gap first, and then start a new batch from after the gap.<br></p> '],
        'delete'    =>  ['Deleting old records','<p>This option will permanently delete all 
            records up to the end of a year.<br>The default year is ten years ago, but this can be changed if you wish.</p>'],
        'death'     =>  ['Recording a death','<p>This action removes a sheep from the \'Sheep\' lists, 
            but pushes it into the \'Off\' lists.<br>  It can still be found by searching for the Tag serial number</p>'],
        'deathsearch'     =>  ['Recording a death','<p>This action removes the selected sheep from the \'Sheep\' lists, 
            but pushes it into the \'Off\' lists.<br>  It can still be found by searching for the Tag serial number.<br>
            Change the date if you don\'t want to use today\'s date.</p>'],
        'date-setter'     =>  ['Setting custom dates','<p>Setting a date range will filter the results shown in the ready made lists -   
            those under the \'Sheep Lists\' and \'Off Lists\' menu items. <br> This can be useful to narrow the field when looking for a
            tag or when preparing a list for printing.<br> 
            Choose a custom range, or either of the previous two years to 1st December (the official Annual Inventory date).</p>
            <p>Reset to the default 10 years range by coming back again later.<br> If sheep do not appear in a list when you expected 
            them to you it may be that you have a custom date set</p>'],
        'replaced'  => ['Replacement tags','<p>These are identified here as belonging to sheep that have a different original tag number form the current one. <br>
            Change the date range to filter results to perhaps only the last 12 months.</p>'],
        'singleoff' => ['Single Batch Tags','<p>Here record the application of singleton EID batch tags to slaughter.<br> 
            Use the correct date and the list will then correspond with your movement records.</p><p>These should normally be
            your home flock number, change this if not.</p>'],
        'replace-a-tag' => ['Tag Replacement','<p>In this context a New sheep is one which has not been entered into the database 
            yet, <br> this can be done here automatically at the same time as entering the new and old tag numbers.</p>'],
        'ewes' => ['Ewes','<p>If Numbers appear to be missing here check you Date Range selection - (Under your Name, menu right hand side).</p>'],
        'tups' => ['Tups','<p>If Numbers appear to be missing here check you Date Range selection - (Under your Name, menu right hand side).</p>'],
        'offlist' => ['Sheep Off','<p>If Numbers appear to be missing here check you Date Range selection - (Under your Name, menu right hand side).</p>'],
        'deadlist' => ['Deaths','<p>If Numbers appear to be missing here check you Date Range selection - (Under your Name, menu right hand side).</p>'],
        'edit' => ['Changing Tags','<p>Clearly this page is for changing tags on a sheep, where you know its previous identity, and
            are giving it new tags.</p>'],
    ];


}
