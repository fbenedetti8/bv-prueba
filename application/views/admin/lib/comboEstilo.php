<div class="row">
	<label><?php echo $label;?>:</label>
	<div class="inputs">
		<span class="input_wrapper">
			<select multiple="multiple" id="<?php echo $name;?>" name="<?php echo $name;?>[]">
				<option value="-1">Seleccionar</option>
				<?php
				if (is_array($data)) {
					foreach ($data as $item){
						
						if(count($values) > 0){
							$esta = "no";
							foreach($values as $value){
								if( $item[$valueField]==$value->{$fk_field} ){
									$option = "<option value='" . $item[$valueField] . "' selected='selected'>" . utf8_decode($item[$captionField]) . "</option>";
									$esta = "si";
									break;
								}
								else{
									$esta = "no";
								}
							}
							if($esta == "no")
								$option = "<option value='" . $item[$valueField] . "'>". utf8_decode($item[$captionField]) . "</option>";
						}
						else{
							$option = "<option value='" . $item[$valueField] . "'>" . utf8_decode($item[$captionField]) . "</option>";
						}
						
						echo $option;
					}
				} else {
					foreach ($data->result() as $row){
						
						if(count($values) > 0){
							$esta = "no";
							foreach($values as $value){
								if($row->{$valueField}==$value->{$fk_field}){
									$option = "<option value='" . $row->{$valueField} . "' selected='selected'>". utf8_decode($row->{$captionField}) . "</option>";
									$esta = "si";
									break;
								}
								else
									$esta = "no";
							}
							if($esta == "no")
								$option = "<option value='" . $row->{$valueField} . "'>". utf8_decode($row->{$captionField}) . "</option>";
						}
						else{
							$option = "<option value='" . $row->{$valueField} . "'>" . utf8_decode($row->{$captionField}) . "</option>";
						}
						
						echo $option;
					}
				}
				?>
			</select>
		</span>
		<span class="system negative" id="error_<?php echo $name;?>"><?php echo $this->lang->line('Please select');?></span> 
	</div>
</div>
