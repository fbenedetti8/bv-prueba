                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Eliminar</th>
                          </tr>
                        </thead>
                        <tbody>
                          <? if (count($miembros)): ?>
                          <? foreach ($miembros as $miembro): ?>
                          <tr>
                            <td><?=$miembro->nombre;?></td>
                            <td><?=$miembro->apellido;?></td>
                            <td><button type="button" class="btnEliminar btn btn-danger" data-ref="<?=$miembro->id;?>">Eliminar</button></td>
                          </tr>
                          <? endforeach; ?>
                          <? else: ?>
                          <tr id="empty" style="padding:25px 0;">
                            <td colspan="3">No hay miembros en este equipo todavía.</td>
                          </tr>
                          <? endif; ?>
                        </tbody>
                      </table>