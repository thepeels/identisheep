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
             (.txt) file. Then open the file in e.g. notepad. <br> To load correctly the file must have EXACTLY 
             TWO lines above the tag list, so adjust this accordingly. <br> The third line must contain all 
             the tag data, and each tag number <br> is separated from the following one with a comma (.csv = 
             comma separated values) like this:- <br> UK012312300051,UK012312300053,UK012312300088,UK012312300151
             <br> Spaces will cause a mis-read, and there is no final comma on the line, it will cause a \'sheep\' 
             with all zeros to be loaded. </p>
             The default date is today, change this if you want to.<br>Enter the holding number and/or name 
             of the destination for the sheep.<br>Select your previously prepared and saved file, and use the 
             \'check\' button to generate a list <br>which you can use to see whether your file is being read 
             correctly.</p>
             If you are satisfied with the list, press the backspace key and then the \'load\' button.<br> Now the 
             list will be loaded into your \'off list\' (Sheep Off Holding) and also removed from the \'all sheep\' 
             list.<br>'],
        'batch'     =>  ['Batch of Sheep onto Holding',',<p>Change the default date if relevant.<br> 
            Enter the details of a batch of sheep where the tag series is continuous with no gaps. <br> 
            If you have a gap, go up to the gap first, and then start a new batch from after the gap.<br></p> '],
        'delete'    =>  ['Deleting old records','<p>This option will permanently delete all 
            records up to the end of a year.<br>The default year is ten years ago, but this can be changed if you wish.</p>'],
        'death'     =>  ['Recording a death','<p>This action removes a sheep from the \'Sheep\' lists, 
            but pushes it into the \'Off\' lists.<br>  It can still be found by searching for the Tag serial number</p>'],
        'datesetter'     =>  ['Setting custom dates','<p>This page allows you to choose a range of dates to show in \'Sheep\' lists,<br> 
            Choose a custom range, either of the previous two years to 1st December ( the official Annual Inventory date).</p>
            <p>Reset to the default 10 years range by coming back again later.<br> If sheep do not appear in a list when you expected 
            them to you it may be that you have a custom date set</p>']
    ];


}
