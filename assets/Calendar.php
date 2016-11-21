<?php

// PHP Calendar Plugin With AJAX Support
// -- itsMeCherry

class Calendar{

    /*
     * Private Properties
     */

    private $month;
    private $monthText;
    private $year;
    //
    private $daysInMonth;
    private $firstDayStart;
    //
    private $currentDay;
    private $currentMonth;
    private $currentMonthText;
    private $currentYear;
    //
    private $previousMonth;
    private $nextMonth;
    private $yearSelectorNext;
    private $yearSelectorPrevious;
    //
    private $timestamp; // we only need this as property for debug()
    private $timestampStartMonth; // we only need this as property for debug()

    /*
     * Methods
     */

    public function __construct($passedMonth = NULL, $passedYear = NULL){


        // overwrite our defaults and store in case we need to use it later

        $this -> currentDay = date("d"); // 01 to 31
        $this -> currentMonth = date("m"); // 01 to 12
        $this -> currentMonthText = date("F"); // January to December
        $this -> currentYear = date("Y"); // 2003

        $this -> setDate($passedMonth, $passedYear);

    }

    public function getDayStart($dayNumerical){
        return gmmktime(0,0,0,$this -> month, $dayNumerical, $this -> year);
    }

    public function getDayEnd($dayNumerical){
        return gmmktime(23,59,59,$this -> month, $dayNumerical, $this -> year);
    }

    public function setDate($suppliedMonth, $suppliedYear){

        // if we have not supplied a month and year, use defaults.

        if($suppliedYear == NULL){
            $this -> year = $this -> currentYear;
        }else{
            $this -> year = $suppliedYear;
        }

        if($suppliedMonth == NULL){

            $this -> month = $this -> currentMonth;
            $this -> monthText = $this -> currentMonthText;
            $this -> timestamp = time();
            $this -> timestampStartMonth = gmmktime(0,0,0,$this -> month, 1, $this -> year);

        }else{

            $this -> month = $suppliedMonth;
            $this -> timestamp = gmmktime(0,0,0,$this -> month,1,$this -> year);
            $this -> monthText = date("F", $this -> timestamp);
            $this -> timestampStartMonth = $this -> timestamp;

        }

        $this -> firstDayStart = date("N", $this -> timestampStartMonth);// the day (mon,tues etc) that the 1st day of the month is on - given in numberical format
        $this -> daysInMonth = date("t", $this -> timestamp);

        // set the neighbour dates

        $this -> previousMonth = ($this -> month) - 1;
        $this -> nextMonth = ($this -> month) + 1;
        $this -> yearSelectorNext = $this -> year;
        $this -> yearSelectorPrevious = $this -> year;

        // now validate them

        if($this -> previousMonth < 1){

            $this -> previousMonth = 12;
            $this -> yearSelectorNext = $this -> year;
            $this -> yearSelectorPrevious = ($this -> year) - 1;

        }elseif($this -> nextMonth > 12){

            $this -> nextMonth = 1;
            $this -> yearSelectorNext = ($this -> year) + 1;
            $this -> yearSelectorPrevious = $this -> year;

        }

    }

    public function displayCalendar($width = "1500px", $height = "950px", array $passedEvents = []){

        print "<div style=\"height:$height;width:$width;margin:0 auto;overflow:auto;\">";

            $this -> displayTopbar();
            $this -> displayMainContent($passedEvents);

        print "</div>";
    }

    /*
     *  Run this only after setting your date via setDate()
     */

    public function debug(){

        print "<br><br><div style=\"color:white;background-color:black;width:250px;\">";
        print "current day -> " . $this -> currentDay . "<br>";
        print "current month -> " . $this -> currentMonth . "<br>";
        print "current month text -> " . $this -> currentMonthText . "<br>";
        print "current year -> " . $this -> currentYear . "<br>";
        print "current timestamp -> " . time() . "<br> <br>";

        print "active month -> " . $this -> month . "<br>";
        print "active month text -> " . $this -> monthText . "<br>";
        print "active year -> " . $this -> year . "<br>";
        //print "active date -> " . $this -> activeDate . "<br>";
        print "active timestamp -> " . $this -> timestamp . "<br><br>";

        print "first day name of the active month -> " . $this -> firstDayStart . "<br>";
        print "days in active month -> " . $this -> daysInMonth;
        print "</div>";

    }

    /*
     * Just in case we want to display the top bar or content box individually in some other page
     * Also.. the below css and html can be modified to suit any preferred design
     */

    public function displayTopbar(){

        print "<div class=\"calendar_topbar\">";

        print "<div class=\"calendar_button\" onclick=\"load_calendar_contents('" . $this -> previousMonth . "', '" . $this -> yearSelectorPrevious . "');\"><i class=\"fa fa-arrow-left\"></i></div>";
        print "<div class=\"calendar_button\" onclick=\"load_calendar_contents('" . $this -> nextMonth. "', '" . $this -> yearSelectorNext . "');\"><i class=\"fa fa-arrow-right\"></i></div>";
        
        print "<p class=\"calendar_title\">" . $this -> monthText . " " . $this -> year . "</p>";
        
        print "<div class=\"calendar_button\" onclick=\"return window.print();\" style=\"float:right;background-color:#d4d4d6;\"><i class=\"fa fa-print\"></i></div>";

        print "</div>";

    }

    public function displayMainContent(array $passedEvents){

        print "<div class=\"calendar_container\">";

            print "<div class=\"calendar_days_columns\">Monday</div>";
            print "<div class=\"calendar_days_columns\">Tuesday</div>";
            print "<div class=\"calendar_days_columns\">Wednesday</div>";
            print "<div class=\"calendar_days_columns\">Thursday</div>";
            print "<div class=\"calendar_days_columns\">Friday</div>";
            print "<div class=\"calendar_days_columns\">Saturday</div>";
            print "<div class=\"calendar_days_columns\">Sunday</div>";
            print "<br>";

            $daysOffset = $this -> firstDayStart - 1; // minus 1 because $firstdaystart INCLUDES the 1st day
            $internalCount = $this -> daysInMonth + $this -> firstDayStart;

            for($boxCounter = 1; $boxCounter < $internalCount; $boxCounter++){

                $dayNumeric = $boxCounter - $daysOffset;

                if($boxCounter < $this -> firstDayStart){

                    print "<div class=\"calendar_days_box\" style=\"background-color:#cecece;\"></div>";

                }else{

                    if($this -> currentDay == $dayNumeric && $this -> month == $this -> currentMonth && $this -> year == $this -> currentYear){ 
                        $borderStyle = "border:1px solid #e26161;"; 
                    }else{ 
                        $borderStyle = "border: 1px solid #ddd;"; 
                    }

                    $dayStart = $this -> getDayStart($dayNumeric);
                    $dayEnd = $this -> getDayEnd($dayNumeric);

                    print "<div class=\"calendar_days_box\" style=\"$borderStyle\" id=\"$boxCounter\" onmouseover=\"select_day_box($boxCounter);\" onmouseout=\"deselect_day_box($boxCounter);\">";

                    print "<p>$dayNumeric</p>";

                    foreach($passedEvents as $passedEvent){

                        if(($passedEvent["date_start"] >= $dayStart && (($passedEvent["date_end"] <= $dayEnd && $passedEvent["date_end"] != NULL) || ($passedEvent["date_start"] <= $dayEnd)))
                           ||
                           (($passedEvent["date_start"] <= $dayStart && $passedEvent["date_start"] != NULL) && ($passedEvent["date_end"] >= $dayEnd || ($passedEvent["date_end"] <= $dayEnd && $passedEvent["date_end"] >= $dayStart)))){

                            if($passedEvent["colour"] != ""){
                                $colour = $passedEvent["colour"];
                            }else{
                                $colour = "#0F2D47";
                            }

                            print "<div class=\"event_block\" style=\"background-color:$colour;\">";
                                print "<p><b>" . $passedEvent["contact"] . " - </b>";
                                print $passedEvent["description"] . "</p>";
                            print "</div>";

                        }

                    }

                    print "</div>";

                }
            }

        print "</div>";

    }

}