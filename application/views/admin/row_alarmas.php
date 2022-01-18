											<div >
												<div style="display: none;">
													<?// pre($row->alarmas); ?>
												</div>
												<? if($row->alarmas->completar_datos_pax):
													//si no completo los datos de pasajeros y faltan 2 dias para la fecha limite de completar ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Faltan completar datos de pasajeros"><i class="glyphicon glyphicon-user"></i></button>
												<? endif; ?>
												
												<? if($row->alarmas->informes): 
													//si tiene informes de pago sin verificar ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=informes');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay informes de pago sin verificar"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												<div style="display: none;"><?=$row->fecha_limite_pago_completo;?></div>
												<? if(@$row->alarmas->fecha_limite_pago_completo): 
													//si tiene pagos pendientes alcanzada la fecha limite ?>
													<button onclick="return false; " type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay pagos pendientes"><i class="glyphicon glyphicon-usd"></i></button>
												<? endif; ?>
												
												<? if($row->alarmas->falta_factura_proveedor): 
													//si el ultimo documento del usuario es un recibo muestro alarma ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=cta_cte');?>'" type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Falta factura del Proveedor"><i class="glyphicon glyphicon-file"></i></button>
												<? endif; ?>
												
												<? //solo para reservas de vaijes que no sean operados por BUENAS VIBRAS
												if($row->alarmas->faltan_cargar_vouchers): 
													//si llego a la fecha limite para mostrar alerta de carga de vouchers ?>
													<button onclick="location.href= '<?=site_url("admin/reservas/edit/".$row->id.'?tab=vouchers');?>'" type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Hay vouchers sin cargar"><i class="glyphicon glyphicon-tags"></i></button>
												<? endif; ?>
												
												<? if($row->alarmas->alerta_llamar_pax){ ?>
													<button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-html="true" data-placement="top" title="Llamar al pasajero"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
												
												<? if($row->alarmas->alerta_reestablecida){ ?>
													<button type="button" class="btn btn-sm btn-success" data-toggle="tooltip" data-html="true" data-placement="top" title="Restablecida / Extendida"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
												
												<? if($row->alarmas->alerta_contestador){ ?>
													<button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" data-html="true" data-placement="top" title="Contestador / Rellamar"><i class="glyphicon glyphicon-earphone"></i></button>
												<? } ?>
											
												<? if(@$row->alarmas->alerta_cupos_vencidos){ ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="La reserva tiene cupos de transporte vencidos"><i class="glyphicon glyphicon-time"></i></button>
												<? } ?>

												
												<? if(@$row->alarmas->tiene_adicionales){ ?>
													<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-html="true" data-placement="top" title="Tiene adicionales contratados"><i class="glyphicon glyphicon-tags"></i></button>
												<? } ?>
											</div>