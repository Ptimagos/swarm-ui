
<!-- Table -->
<table class="table table-striped no-margin-bottom">
	<tr>
		<th class='col-xs-1'></th>
		<th class='col-xs-2'>Key</th>
		<th class='col-xs-8'>Value</th>
		<th class='col-xs-1'></th>
	</tr>
	<?PHP
		$infoContainer = array(
			'Path' => $containerDocker['responce']['Path'],
			'Args' => $containerDocker['responce']['Args'],
			'Exposed Port' => $containerDocker['responce']['Config']['ExposedPorts'],
			'Port binding' => $containerDocker['responce']['NetworkSettings']['Ports'],
			'Environment' => $containerDocker['responce']['Config']['Env'],
			'Mounts' => $containerDocker['responce']['Mounts']
		);
		$nbInfoContainer = count($infoContainer);
		foreach ($infoContainer as $key => $value){
			print "<tr>";
			print "<td></td>";
			print "<td>".$key.":</td>";
			print "<td>";
			if (!is_array($value)){
				print $value;
			} else {
				switch ($key) {
					case 'Exposed Port':
						foreach ($value as $keyPort => $port){
							print $keyPort."<br/>";
						}
						break;
					case 'Environment':
						print "<pre>";
						$arraylength = count($containerDocker['responce']['Config']['Env']);
						for ($t=0;$t<$arraylength;$t++){
							print $containerDocker['responce']['Config']['Env'][$t]."<br/>";
						}
						print "</pre>";
						break;
					case 'Args':
						$args = "";
						$arraylength = count($containerDocker['responce'][$key]);
						for ($t=0;$t<$arraylength;$t++){
							$args .= $containerDocker['responce'][$key][$t]." ";
						}
						print $args;
						break;
					case 'Mounts':
						$mount = "";
						$arraylength = count($containerDocker['responce'][$key]);
						for ($t=0;$t<$arraylength;$t++){
							$mount .= $containerDocker['responce'][$key][$t]['Source']." => "
								."<span class='label label-default'>"
								.$containerDocker['responce'][$key][$t]['Destination']."</span><br/>";
						}
						print $mount;
						break;
					case 'Port binding':
						foreach ($value as $keyPort => $port){
							if (!is_array($port)){
								continue;
							}
							print $keyPort." => <span class='label label-default'>"
								.$containerDocker['responce']['NetworkSettings']['Ports'][$keyPort][0]['HostIp']
								.":".$containerDocker['responce']['NetworkSettings']['Ports'][$keyPort][0]['HostPort']."</span><br/>";
						}
					default:
						$arraylength = count($containerDocker['responce'][$key]);
						for ($t=0;$t<$arraylength;$t++){
							print $containerDocker['responce'][$key][$t]." ";
						}
						break;
				}
			}
			print "</td>";
			print "<td></td>";
			print "</tr>";
		}
	?>
	
</table>