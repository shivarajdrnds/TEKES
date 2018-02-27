<?php
$emp_no = $this->session->userdata('username');

$user_role = $this->session->userdata('user_role');
if ($user_role == 6) {
    ?>
    <div class="main-content">
        <div class="container">
            <section class="topspace blackshadow bg-white"> 
                <div class="col-md-12">
                    <div class="row">
                        <div class="panel-heading info-bar" >
                            <div class="panel-title">
                                <h2>Leaves</h2>
                            </div>
                        </div>

                        <!-- Leave Table Format Start Here -->

                        <table class="table table-bordered datatable" id="emp_leave_table">
                            <thead>
                                <tr class="replace-inputs">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                                <tr>
                                    <th>S.No</th>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th>Reporting Manager</th>
                                    <!--<th>Leave Type</th>-->
                                    <th>Duration</th>
                                    <th>Pattern</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>No of Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $this->db->order_by('L_Id', 'desc');
                                $this->db->where('Status', 1);
                                $q = $this->db->get('tbl_leaves');
                                $count = $q->num_rows();
                                if ($count > 0) {
                                    foreach ($q->Result() as $row) {

                                        $Leave_Id = $row->L_Id;
                                        $employee_id = $row->Employee_Id;
                                        $this->db->where('employee_number', $employee_id);
                                        $q_code = $this->db->get('tbl_emp_code');
                                        foreach ($q_code->result() as $row_code) {
                                            $emp_code = $row_code->employee_code;
                                        }

                                        $get_empdata = array(
                                            'Emp_Number' => $employee_id,
                                            'Status' => 1
                                        );

                                        $this->db->where($get_empdata);
                                        $q_employee = $this->db->get('tbl_employee');
                                        foreach ($q_employee->result() as $row_employee) {
                                            $Emp_FirstName = $row_employee->Emp_FirstName;
                                            $Emp_Middlename = $row_employee->Emp_MiddleName;
                                            $Emp_LastName = $row_employee->Emp_LastName;
                                        }

//                                    $emp_report_to_id = $row->Reporting_To;
                                        $this->db->order_by('Career_Id', 'asc');
                                        $this->db->where('Employee_Id', $employee_id);
                                        $this->db->limit(1);
                                        $q_career = $this->db->get('tbl_employee_career');
                                        foreach ($q_career->result() as $row_career) {
                                            $emp_report_to_id = $row_career->Reporting_To;
                                        }
                                        $this->db->where('Emp_Number', $emp_report_to_id);
                                        $q_emp = $this->db->get('tbl_employee');
                                        foreach ($q_emp->result() as $row_emp) {
                                            $emp_reporting_firstname = $row_emp->Emp_FirstName;
                                            $emp_reporting_lastname = $row_emp->Emp_LastName;
                                            $emp_reporting_middlename = $row_emp->Emp_MiddleName;
                                        }

                                        $Leave_Type_Id = $row->Leave_Type;
                                        $this->db->where('L_Id', $Leave_Type_Id);
                                        $q_leave_type = $this->db->get('tbl_leavetype');
                                        foreach ($q_leave_type->result() as $row_leave_type) {
                                            $Leave_Title = $row_leave_type->Leave_Title;
                                        }

                                        $Leave_Duration = $row->Leave_Duration;
                                        $Leave_Pattern = $row->Leave_Pattern;
                                        $Leave_From1 = $row->Leave_From;
                                        $Leave_From = date("d-M-Y", strtotime($Leave_From1));

                                        $Leave_To1 = $row->Leave_To;
                                        $Leave_To_include = date('Y-m-d', strtotime($Leave_To1 . "+1 days"));
                                        $Leave_To = date("d-M-Y", strtotime($Leave_To1));

                                        if ($Leave_Duration == "Full Day") {
                                            $interval = date_diff(date_create($Leave_To_include), date_create($Leave_From1));
                                            $No_days = $interval->format("%a");
                                        } else {
                                            $No_days = 0.5;
                                        }

                                        $Reason = $row->Reason;
                                        $Approval = $row->Approval;
                                        $status = $row->Status;
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $emp_code . $employee_id; ?></td>
                                            <td><?php echo $Emp_FirstName . " " . $Emp_LastName . " " . $Emp_Middlename; ?></td>
                                            <td> 
                                                <?php
                                                echo $emp_reporting_firstname . " " . $emp_reporting_lastname . " " . $emp_reporting_middlename;
                                                ?>
                                            </td>
                                            <!--<td><?php //echo $Leave_Title; ?></td>-->
                                            <td><?php echo $Leave_Duration; ?></td>
                                            <td><?php echo $Leave_Pattern; ?></td>
                                            <td><?php echo $Leave_From; ?></td>
                                            <td><?php echo $Leave_To; ?></td>
                                            <td><?php echo $No_days; ?></td>
                                            <td><?php echo $Reason; ?></td>
                                            <td>
                                                <?php
                                                if ($Approval == "Request") {
                                                    echo "Processing...";
                                                }if ($Approval == "Yes") {
                                                    echo "Approved";
                                                }if ($Approval == "No") {
                                                    echo "Not Approved";
                                                }
                                                if ($Approval == "Cancel") {
                                                    echo "Canceled";
                                                }
                                                ?>
                                            </td>
                                            
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>

                        </table>

                        <!-- Leave Table Format End Here -->
                    </div>
                </div>
            </section>


            <!-- Table Script -->
            <script type="text/javascript">
                jQuery(document).ready(function ($)
                {
                    var table = $("#emp_leave_table").dataTable({
                        "sPaginationType": "bootstrap",
                        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "bStateSave": true
                    });

                    table.columnFilter({
                        "sPlaceHolder": "head:after"
                    });
                });
            </script>
        <?php } ?>

