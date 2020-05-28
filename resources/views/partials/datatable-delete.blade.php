<script>
    $(() => {
        $('body').on('click','a.btn-delete-datatable', function(e) {
            let that = $(e.currentTarget);
            e.preventDefault()
            swal({
                title: 'Are you sure want to delete this {{ $text }}?',
                icon: 'warning',
                buttons: true,
            }).then((result) => {
                if (result) {
                    $.ajax({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: that.attr('data-href'),
                        type: 'DELETE'
                    })
                    .done(() => {
                        swal({
                            title: 'Data {{ $text}} deleted successfully',
                            icon: 'success'
                        }).then((nextResult) => {
                          if (nextResult) {
                            $('{{ $table }}').DataTable().ajax.reload();
                          }                        
                        })
                    })
                    .fail(() => {
                        swal({
                            title: 'An error occured during delete data, please try again...',
                            icon: 'warning'
                        })
                    })
                }
            })
        })
    })
</script>