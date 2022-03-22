<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"> -->
   <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <div class="wrap">
            <h1>API Message History</h1>
            
        <div class="table-responsive">
            <table id="messages" class="table table-bordered table-stripped table-hover">
              <thead class="table-primary">
              <tr>
                    <th>Created at</th>
                    <th>Phone</th>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Status</th>
              </tr>
              </thead>
              <?php $results = getsms_logs() ?>
              <tbody id="msg_body">
                  <?php  foreach( $results as $result ):?>
                  <tr>
                      <td><?php echo $result->created_at?></td>
                      <td><?php echo $result->phone?></td>
                      <td><?php echo $result->sender?></td>
                      <td><?php echo $result->message?></td>
                      <td><?php echo $result->status?></td>
                  </tr>
                  <?php endforeach ?>
              </tbody>
        </table>
        </div>
        </div>
        <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#messages').DataTable( {
                    "order": [[ 0, "desc" ]]
                } );
            } );
        </script>
        </body>

        