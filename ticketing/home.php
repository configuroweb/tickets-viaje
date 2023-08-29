<?php include '../db_connect.php' ?>
<style>
  span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
  }

  .bg-gradient-primary {
    background: rgb(119, 172, 233);
    background: linear-gradient(149deg, rgba(119, 172, 233, 1) 5%, rgba(83, 163, 255, 1) 10%, rgba(46, 51, 227, 1) 41%, rgba(40, 51, 218, 1) 61%, rgba(75, 158, 255, 1) 93%, rgba(124, 172, 227, 1) 98%);
  }

  .btn-primary-gradient {
    background: linear-gradient(to right, #1e85ff 0%, #00a5fa 80%, #00e2fa 100%);
  }

  .btn-danger-gradient {
    background: linear-gradient(to right, #f25858 7%, #ff7840 50%, #ff5140 105%);
  }

  .station-field .item {
    cursor: pointer;
  }

  .station-field .item:hover {
    opacity: .7;
  }

  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .pax {
    width: 35px;
    text-align: center;
  }

  .reserved table p {
    margin: unset;
  }
</style>
<?php
if (isset($_GET['id'])) :
  $order = $conn->query("SELECT * FROM orders where id = {$_GET['id']}");
  foreach ($order->fetch_array() as $k => $v) {
    $$k = $v;
  }
  $items = $conn->query("SELECT o.*,p.name FROM order_items o inner join products p on p.id = o.product_id where o.order_id = $id ");
endif;
?>
<div class="container">
  <form action="" id="manage-ticket">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <b>Venta de Tickets</b>
            <?php if ($_SESSION['login_station_id'] == 0) : ?>
              <span class="float:right"><a class="btn btn-warning btn-sm col-sm-3 float-right" href="../index.php" id="">
                  <i class="fa fa-home"></i> Inicio
                </a></span>
            <?php endif; ?>
          </div>
          <div class="card-body station-field">
            <?php if ($_SESSION['login_station_id'] <= 0) : ?>
              <div class="form-group">
                <label for="" class="control-label"><b>Origen del Viaje</b></label>
                <select id="origin_station" class="custom-select-sm select2" name="origin_station">
                  <option value=""></option>
                  <?php
                  $station = $conn->query("SELECT * FROM stations  order by station asc");
                  while ($row = $station->fetch_assoc()) :
                  ?>
                    <option value="<?php echo $row['id'] ?>"><?php echo ucwords($row['station']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            <?php else : ?>
              <input type="hidden" id="origin_station" name="origin_station" value="<?php echo $_SESSION['login_station_id'] ?>">
            <?php endif; ?>
            <div class="row justify-content-center align-center">
              <label for="" class="control-label mr-2 mt-2"><b>Buscar ruta</b></label>
              <input type="text" id="filter" class="form-control-sm col-sm-4">
            </div>
            <div class="row">
              <?php
              $station = $conn->query("SELECT * FROM stations " . ($_SESSION['login_station_id'] > 0 ? " where id!={$_SESSION['login_station_id']} " : "") . " order by station asc");
              while ($row = $station->fetch_assoc()) :
              ?>
                <div class="col-md-4 py-2">
                  <div class="card bg-gradient-warning border item" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['station'] ?>">
                    <div class="card-body">
                      <div class="row justify-content-center align-center">
                        <h5 class="text-white"><b><?php echo ucwords($row['station']) ?></b></h5>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
            <div class="row">
              <div id="msg" class="col-md-12"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-header">
            <b>Tickets</b>
          </div>
          <div class="card-body reserved">
            <div class="d-flex w-100 ">
              <input type="hidden" name="destination_id" value="">
              <span><b>Destino: <span id="dname"></span></b></span>
            </div>
            <table class="table table-condensed">
              <thead>
                <tr>
                  <th>Tarifa</th>
                  <th>Tipo de Pasajero</th>
                  <th>Costo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="d-flex">
                      <span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
                      <input type="number" name="pax[1]" class="form-control-xs pax" value="0">
                      <span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
                    </div>
                  </td>
                  <td>
                    <p>Adultos</p>
                    <input type="hidden" name="price[1]" class="form-control-xs" value="0">
                    <small class="price" id="price_adult"></small>
                  </td>
                  <td>
                    <p class="text-right amount" id="amount_adult">0.00</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex">
                      <span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
                      <input type="number" name="pax[2]" class="form-control-xs pax" value="0">
                      <span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
                    </div>
                  </td>
                  <td>
                    <p>Estudiantes</p>
                    <input type="hidden" name="price[2]" class="form-control-xs" value="0">
                    <small class="price" id="price_student"></small>
                  </td>
                  <td>
                    <p class="text-right amount" id="amount_student">0.00</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex">
                      <span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
                      <input type="number" name="pax[3]" class="form-control-xs pax" value="0">
                      <span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
                    </div>
                  </td>
                  <td>
                    <p>Adulto Mayor</p>
                    <input type="hidden" name="price[3]" class="form-control-xs" value="0">
                    <small class="price" id="price_senior"></small>
                  </td>
                  <td>
                    <p class="text-right amount" id="amount_senior">0.00</p>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2">Total</td>
                  <td>
                    <input type="hidden" name="total_amount" value="0">
                    <p class="text-right" id="tamount">0.00</p>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="card-footer">
            <div class="col-lg-12 d-flex justify-content-center align-center">
              <button class="btn btn-warning" type="button" id="pay_now">Pagar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="modal fade" id="pay_modal" role='dialog'>
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b>Pagar</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="form-group">
            <label for="">Monto a pagar</label>
            <input type="number" class="form-control text-right" id="apayable" readonly="" value="">
          </div>
          <div class="form-group">
            <label for="">Importe recibido</label>
            <input type="text" class="form-control text-right" id="tendered" value="" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="">Cambio</label>
            <input type="text" class="form-control text-right" id="change" value="0.00" readonly="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning btn-sm" form="manage-ticket">Pagar</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<script>
  var total;
  qty_func();
  calc();
  $('#origin_station').trigger('change')
  $('#filter').keyup(function() {
    var filter = $(this).val()
    $('.station-field .item').each(function() {
      var txt = $(this).text()
      if ((txt.toLowerCase()).includes(filter.toLowerCase()) == true) {
        $(this).parent().toggle(true)
      } else {
        $(this).parent().toggle(false)
      }
    })
    if ($('.station-field .item:visible').length > 0) {
      $('.station-field #msg').html('')
    } else {
      $('.station-field #msg').html('<div class="row justify-content-center align-center"><h4 class="text-center">Sin resultado</h4></div>')
    }
  })
  $('#origin_station').change(function() {
    $('.station-field .item').parent().toggle(true)
    $('.station-field .item[data-id="' + $('#origin_station').val() + '"]').parent().toggle(false)
  })
  $('.station-field .item').click(function() {
    start_load()
    if ($('#origin_station').val() == '') {
      alert_toast('Selecciona la ruta de origen', 'danger');
      end_load()
      return false;
    }
    $.ajax({
      url: '../ajax.php?action=get_price',
      method: 'POST',
      data: {
        origin_id: $('#origin_station').val(),
        destination_id: $(this).attr('data-id')
      },
      success: function(resp) {
        if (resp) {
          $('.pax').each(function() {
            $(this).val(0).trigger('change')
          })
          resp = JSON.parse(resp)
          $('[name="price[1]"]').val(parseFloat(resp.adult_price).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }))
          $('[name="price[2]"]').val(parseFloat(resp.student_price).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }))
          $('[name="price[3]"]').val(parseFloat(resp.senior_price).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }))
          $('#price_adult').text(parseFloat(resp.adult_price).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }))
          $('#price_student').text(parseFloat(resp.student_price).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }))
          $('#price_senior').text(parseFloat(resp.senior_price).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }))
          $('#dname').text(resp.sname)
          $('[name="destination_id"]').val(resp.station_to)
        }
      },
      complete: function() {
        end_load();
      }
    })
  })

  function qty_func() {
    $('.reserved .btn-minus').click(function() {
      if ($('#origin_station').val() == '') {
        alert_toast("No has elegido la ruta de destino", 'danger')
        return false;
      }
      var qty = $(this).siblings('input').val()
      qty = qty > 0 ? parseInt(qty) - 1 : 0;
      $(this).siblings('input').val(qty).trigger('change')
      calc()
    })
    $('.reserved .btn-plus').click(function() {
      if ($('#origin_station').val() == '') {
        alert_toast("No has elegido la ruta de destino", 'danger')
        return false;
      }
      var qty = $(this).siblings('input').val()
      qty = parseInt(qty) + 1;
      $(this).siblings('input').val(qty).trigger('change')
      calc()
    })

  }

  function calc() {
    $('.pax').each(function() {
      $(this).change(function() {
        var tr = $(this).closest('tr');
        var qty = $(this).val();
        var price = tr.find('.price').text()
        var amount = parseFloat(qty) * parseFloat(price);
        amount = amount > 0 ? amount : 0;
        tr.find('.amount').text(parseFloat(amount).toLocaleString("en-US", {
          style: 'decimal',
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        }))
      })
    })
    var total = 0;
    $('.amount').each(function() {
      var amount = $(this).text()
      amount = amount.replace(/,/g, '')
      total = parseFloat(total) + parseFloat(amount)
    })
    $('[name="total_amount"]').val(total)
    $('#tamount').text(parseFloat(total).toLocaleString("en-US", {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }))
  }
  $('#save_order').click(function() {
    $('#tendered').val('').trigger('change')
    $('[name="total_tendered"]').val('')
    $('#manage-order').submit()
  })
  $("#pay_now").click(function() {
    start_load()
    var amount = $('[name="total_amount"]').val()
    if (amount <= 0) {
      alert_toast("Agrega los tiquetes primero", 'danger')
      end_load()
      return false;
    }
    $('#apayable').val(parseFloat(amount).toLocaleString("en-US", {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }))
    $('#pay_modal').modal('show')
    setTimeout(function() {
      $('#tendered').val('').trigger('change')
      $('#tendered').focus()
      end_load()
    }, 500)

  })
  $('#tendered').keyup('input', function(e) {
    if (e.which == 13) {
      $('#manage-order').submit();
      return false;
    }
    var tend = $(this).val()
    tend = tend.replace(/,/g, '')
    $('[name="total_tendered"]').val(tend)
    if (tend == '')
      $(this).val('')
    else
      $(this).val((parseFloat(tend).toLocaleString("en-US")))
    tend = tend > 0 ? tend : 0;
    var amount = $('[name="total_amount"]').val()
    var change = parseFloat(tend) - parseFloat(amount)
    $('#change').val(parseFloat(change).toLocaleString("en-US", {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }))
  })

  $('#tendered').on('input', function() {
    var val = $(this).val()
    val = val.replace(/[^0-9 \,]/, '');
    $(this).val(val)
  })
  $('#manage-ticket').submit(function(e) {
    e.preventDefault();
    start_load()
    $.ajax({
      url: '../ajax.php?action=save_ticket',
      method: 'POST',
      data: $(this).serialize(),
      success: function(resp) {
        if (resp) {
          alert_toast("Datos guardados correctamente", 'success')
          setTimeout(function() {
            var nw = window.open('../receipt.php?id=' + resp, "_blank", "width=900,height=600")
            setTimeout(function() {
              nw.print()
              setTimeout(function() {
                nw.close()
                location.reload()
              }, 500)
            }, 500)
          }, 500)
        }
      }
    })
  })
</script>