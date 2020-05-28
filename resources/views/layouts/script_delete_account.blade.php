<script>
    $(() => {
        $('body').on('click','.delete-account', function(e) {
            let that = $(e.currentTarget);
            e.preventDefault()
            swal({
                title: 'Are you sure want to delete this account?',
                icon: 'warning',
                buttons: true,
            }).then((result) => {
                if (result) {
                    $.ajax({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('user.destroy', \Auth::user()->id) }}',
                        type: 'DELETE'
                    })
                    .done(() => {
                        swal({
                            title: 'Account deleted successfully',
                            icon: 'success'
                        }).then((nextResult) => {
                          if (nextResult) {
                            window.location = '{{ url('') }}'
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