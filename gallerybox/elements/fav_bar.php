<?php   $userFavsThis = $this->controller->userFavsThis($favu, $favfID); $u = new User();?>
<div class="gbx-fav-wrapper">
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
		<?php   $action = $this->action('add_fav',$favfID); ?>
		<?php   if ($favu->isRegistered() && !$userFavsThis) { ?><a href="<?php  echo $action?>" class="gbx-fav-link">
		<img src="<?php  echo BASE_URL.DIR_REL?>/concrete/images/icons/star_grey.png" style="margin-right: 12px;vertical-align:middle;" />
		</a><?php  }else{?>
		<img src="<?php  echo BASE_URL.DIR_REL?>/concrete/images/icons/star_yellow.png" style="margin-right: 12px;vertical-align:middle;" />
		<?php   }?>
		</td>
		<td>
		<p>
		
		<?php  


				$favers = $this->controller->getUsersThatFav($favfID); 

				
				if(count($favers) > 0){
						$ci = 1;
						foreach($favers as $faver){
						$favui = UserInfo::getByID($faver['uID']);
							if ($favui->getAttribute('first_name') == ''){
								$favername =  $favui->getUserName();
							}else{
								$favername = $favui->getAttribute('first_name').' '.$favui->getAttribute('last_name');
							}
							
							if($faver['uID'] == $u->getUserID()){$favername = 'You';}
							echo '<a href="'.View::url('/profile',$faver[0]['uID']).'">'.$favername.'</a>';
							
							if(count($favers) > $ci){
								if (count($favers) == 2){
									echo ' and ';
								}else{
									echo ', ';
								}
							}
							$ci++;
							if (count($favers) == $ci){
									echo 'and ';
							}
						}
				
				
				?> added this as a favorite.

<?php  }?>
		
		
		<?php  
		if ($favu->isRegistered() && !$userFavsThis) { ?>
			&nbsp;&nbsp;<a href="<?php   echo $action;?>" class="gbx-fav-link">Add to favorites</a>
		
		<?php   } ?>

		</p>
		</td>
	</tr>
	</table>
</div>