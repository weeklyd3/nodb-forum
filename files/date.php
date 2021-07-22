<?php
function week_date_selectbox( $time_offset, $name )
    {
    if( isset( $time_offset ) )
        $t = time() + $time_offset;
    else
        $t = time();

    $wday = array("Sunday, ","Monday, ","Tuesday, ","Wednesday, ","Thursday, ","Friday, ","Saturday, ");
    $mon = array("January ","February ","March ","April ","May ","June ","July ","August ","September ","October ","November ","December ");
    $mybox = "<select id=\"$name\" name=\"$name\">\n";
	$mybox.='<option value="2021-01-19">Earlier than listed date</option>';
    for($ii = 0; $ii > - 50; $ii--)
        {
        $tarr = localtime( $t + $ii * 86400 - 86400 * 86400, 1 );
        if( $tarr["tm_wday"] == 0 )
            {
            // found Sunday, now make the week's strings
            for($jj = 0; $jj < 365 * 20 + 15; $jj++)
                {
                $tarr = localtime( $t + ($jj + $ii) * 86400, 1 );
                $mybox .= sprintf( " <option value=\"%04d-%02d-%02d\">%s%s%d, %d </option>\n",
                        ((int)$tarr["tm_year"] + 1900),
                        $tarr["tm_mon"],
                        ((int)$tarr["tm_mday"] + 1),
                        $wday[$tarr["tm_wday"]],
                        $mon[$tarr["tm_mon"]],
                        (int)$tarr["tm_mday"],
                        ((int)$tarr["tm_year"] + 1900) );
                }
            break;
            }
        }
    $mybox .= "</select>\n";

    return $mybox;
    }
	echo week_date_selectbox(0, 'date');