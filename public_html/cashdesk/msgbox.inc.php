<?
/*******************************************************************************
* CASH DESK - MESSAGE BOX DEFINITION                                           *
*                                                                              *
* Version: 1.0                                                                 *
* Date:    21.11.2018                                                          *
* Author:  Unknown                                                             *
*******************************************************************************/

class msgBox{
	var $msgButtons;
	var $msgPrompt;	
	var $msgTitle;
	var $msgIcon;
	var $msgLinks;
	function msgBox($prompt,$buttons,$title="Application Message"){
		switch($buttons){
			case "OKOnly" :
				$this->msgButtons=array("OK");
				$this->msgIcon="C";
				break;
			case "OKCancel":
				$this->msgButtons=array("OK","Cancel");
				$this->msgIcon="s";
				break;
			case "Fault":
				$this->msgButtons=array("OK");
				$this->msgIcon="D";
				break;			
			case "AbortRetryIgnore":
				$this->msgButtons=array("Abort","Retry","Ignore");
				$this->msgIcon="x";
				break;
			case "YesNoCancel":
				$this->msgButtons=array("Yes","No","Cancel");
				$this->msgIcon="s";
				break;
			case "YesNo":
				$this->msgButtons=array("Yes","No");
				$this->msgIcon="s";
				break;
			case "RetryCancel":
				$this->msgButtons=array("Retry","Cancel");
				$this->msgIcon="r";
				break;
		} // end switch
		
		// set the title
		$this->msgPrompt=$prompt;
		$this->msgTitle=$title;
	}
	//
	function makeLinks($linksArray){
		$this->msgLinks=$linksArray;
	}	
	
	function showMsg(){
		echo "&nbsp;";
		echo "<br>";
		echo "<p>";
		
		echo "<table width=\"320\"  border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\" class=\"msg\">";
		echo "  <tr>";
		echo "	<td class=\"msgTitle\" colspan=\"2\" align=\"left\"><b>".$this->msgTitle."&nbsp;</b></td>";
		echo "  </tr>";
		echo "  <tr>";
		echo "	<td width=\"5%\">";
		echo "<span class=\"msgIcon\">".$this->msgIcon."</span>";
		echo "  </td>";
		echo "	<td valign=\"top\">".$this->msgPrompt."</td>";
		echo "  </tr>";
		echo "  <tr>";
		echo "<td colspan=\"2\" valign=\"top\" align=\"center\">";
			for($idx=0;$idx<count($this->msgButtons);$idx++){
				echo "<span class=\"msgButton\">";
				echo "<a href=\"".$this->msgLinks[$idx]."\" class=\"msglinks\">";			
				echo $this->msgButtons[$idx];
				echo "</a>";
				echo "</span>";
				echo "&nbsp;";
			}
		echo "</td>";
		echo "  </tr>";
		echo "</table>";
	}
}
?>
