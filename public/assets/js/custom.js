/** default function & event **/

// set alertify default position
alertify.set('notifier','position', 'top-center');

// change date from db to date indonesia format
function tglIndo(time){
    if(time){
        // let namaHari    = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        let namaBulan   = ['Januari', 'Februari', 'Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
            dateTime = new Date(time),
            tanggal  = dateTime.getDate(),
            hari     = dateTime.getDay(),
            bulan    = dateTime.getMonth(),
            tahun    = dateTime.getFullYear(),
            jam      = ("0" + dateTime.getHours() ).slice(-2),
            menit    = ("0" + dateTime.getMinutes() ).slice(-2),
            detik    = ("0" + dateTime.getSeconds() ).slice(-2);

        return `${tanggal} ${namaBulan[bulan]} ${tahun} ${jam}:${menit}:${detik}` ;    
    }
    
    return '';    
}

// clear every input and clear pond element on close modal
$(document).on('hidden.bs.modal','.modal', function () {
$(this)
  .find("input[type!=hidden],textarea")
     .val('')
     .removeClass('is-valid')
     .removeClass('is-invalid')
     .end()
  .find("input[type=checkbox], input[type=radio]")
     .prop("checked", "")
     .end()
  .find("select")
     .val(null)
     .removeClass('is-valid')
     .removeClass('is-invalid')
     .trigger('change.select2')
     .end()
  .find('.invalid-feedback')
    .html('')
    .end()
  .find('input[type=file]')
    .fileinput('clear');
})

// return html for online status
function onlineStatus(status) {
  if(status) return '<span class="badge badge-success"><strong>Online</strong></span>';
  return '<span class="badge badge-danger"><strong>Offline</strong></span>'; 
}

function blockPage(){
	$.blockUI({ 
        css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        },
        baseZ: 2000
    }); 
}

// unblock page
function unblockPage() {
    $.unblockUI();
}

// default error when save/update failed
function errorAjax(error, msg) {
    console.log(error)
    unblockPage()
    var notification = alertify.message(
      '<span class="fas fas fa-times"> </span> &nbsp; Failed to '+ msg +' data',
      5,
    );
}

// default when save/update success
function successAjax(modal, msg, response) {
    if(response.status === true) {
      var notification = alertify.message(
        '<span class="fas fas fa-check"> </span> &nbsp; Successfully '+ msg +' data',
        5,
      );
      unblockPage()
      $('#' + modal).modal('toggle');
      $('#table').DataTable().ajax.reload();
    } else {
      errorAjax(response, msg)
    }
    
}
