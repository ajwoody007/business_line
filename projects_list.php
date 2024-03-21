<?php ob_start(); session_start(); if (!defined("INI_ARRAY")) { define("INI_ARRAY", parse_ini_file('../../local/hub/hub.ini')); }

# projectss_list.php
# list page for the projects asset
# written by Andy Wood July 2017 

    // load a template with HTML header details and settings for every page
    include_once('page_components.php'); 
    ?>

    <?php    
    
    // security and other settings unique to this page
    $page_alias = INI_ARRAY['projects_list'];
    $page_security_level = 10;
    $role_owner = 13;
    include_once(INI_ARRAY['page_security']);

    // cleared security, run the includes
    $_SESSION['breadcrumb'] = "projects_list";
    $full_name = $_SESSION['fullname'];

    include_once(INI_ARRAY['projects_nav']);
    include_once(INI_ARRAY['projects_ctl']);
    include_once(INI_ARRAY['projects_mdl']);
    include_once('header.php');

    // initial settings for the table display
    if (isset($_SESSION['project_sort_direction'])) { $sort_direction = $_SESSION['project_sort_direction']; } else {$sort_direction = 'ASC'; }
    if (isset($_SESSION['project_sort_column'])) { $sort_order = $_SESSION['project_sort_column'] . ' ' . $sort_direction ; } else { $sort_order = ' project_planned_end_date DESC'; }

    // get the list of currently active projects
    $arrProjects = projects_ctl::getLiveProjectsList($sort_order);
    $intProjects = 0; if ($arrProjects) { while ($rowProjects = $arrProjects->fetch(PDO::FETCH_ASSOC)) { $intProjects++ ; } $arrProjects->execute(); }
    $totalBudget = 0;
    
?>

    <div class="search_dialog" id="srchResults"></div>    

    <!-- Add js files specific to this page -->

    <script type="text/javascript" src="<?= INI_ARRAY['projects_js']; ?>"></script>
    
    <div class="col-xs-10 col-xs-offset-1">

            <div class='pageBorder'>

                <!-- top line header -->
                
                <div class="menuRow">
                    
                    <div class="col-xs-6 textLeft">
                        
                        <span class="page_title">Projects List</span>
                        
                    </div>

                    <div class="col-xs-6 textRight">
                        
                        <form method='post'>
                            <span class='textRight'><button class='btnMenu <?= $viewRW ; ?>' name='btnAddProject'>Create</button></span>
                        </form>
                        
                    </div>

                </div>

                <!-- main projects list -->

                <form method='post'>

                    <!-- an external file with search and filter options -->
                    <?php include_once('projects_filter_ribbon.php'); ?>
                    
                    <table class='projectsTable'>
                        
                        <tr>
                            <th width='5%' class='heading'><a class='link2' onclick=sortProjectColumn('project_name')>Project Title</a></th>
                            <th width='5%' class='heading'><a class='link2' onclick=sortProjectColumn('company_name')>Customer</a></th>
                            <th width='5%' class='heading'><a class='link2' onclick=sortProjectColumn('current_phase')>Phase</a></th>
                            <th width='5%' class='heading'><a class='link2' onclick=sortProjectColumn('project_status_value')>Status</a></th>
                            <th width='5%' class='heading'><a class='link2' onclick=sortProjectColumn('due_by')>Planned Due Date</a></th>
                            <th width='5%' class='heading'><a class='link2' onclick=sortProjectColumn('project_budget')>Budget</a></th>
                        </tr>

                        <?php

                            $intCount=0;

                            date_default_timezone_set('Europe/London');
                            $dateNow = date('Y-m-d H:i');
                            $date_due = new DateTime($dateNow);
                            
                            while ($rowProjects = $arrProjects->fetch(PDO::FETCH_ASSOC)) {
                                
                                $project_id = $rowProjects['project_id'];
                                $customer_name = $rowProjects['company_name'];
                                $project_name = $rowProjects['project_name'];
                                $current_phase = $rowProjects['current_phase'];
                                $current_status = $rowProjects['project_status_value'];
                                $due_by = $rowProjects['due_by'];
                                $budget = $rowProjects['project_budget'];
                                $totalBudget = $totalBudget + $budget;
                                
                                // if a planned completion date is in the past and the project is not deployed, completed or cancelled, the whole row should have red text
                                
                                date_default_timezone_set("Europe/London");
                                $datToday = strtotime(Date('Y-m-d'));
                                $datDueByRaw = strtotime($due_by);
                                $datDueBy = date('d/m/Y',$datDueByRaw);
                                
                                if ($datToday > $datDueByRaw && $current_phase != 'Deployed' && $current_phase != 'Cancelled') {
                                    $rowText = "style='color:#FF0000;'";
                                } else {
                                    $rowText = '';
                                }
                                
                                echo "<tr class='resultRow' $rowText>";
                                echo "<td $rowText><button class='btnList' name='btnEditProject' value=" . $project_id . ">" . $project_name . "</button></td>";
                                echo "<td $rowText><button class='btnList' name='btnEditProject' value=" . $project_id . ">" . $customer_name . "</button></td>";
                                echo "<td $rowText><button class='btnList' name='btnEditProject' value=" . $project_id . ">" . $current_phase . "</button></td>";
                                echo "<td $rowText><button class='btnList' name='btnEditProject' value=" . $project_id . ">" . $current_status . "</button></td>";
                                echo "<td $rowText><button class='btnList' name='btnEditProject' value=" . $project_id . ">" . $datDueBy . "</button></td>";
                                echo "<td $rowText><button class='btnList' name='btnEditProject' value=" . $project_id . ">&pound;" . number_format($budget, 2) . "</button></td>";
                                
                                echo "</tr>";
                                
                                
                            }
                            
                        ?>

                    </table>

                </form>
                
                <!-- a summary at the bottom of the table -->
                <div class="info_panel textRight">This list contains <?= $intProjects . ' ' ; ?> project(s) with a total budget of &pound;<?= number_format($totalBudget,2) ; ?>.</div>

            </div>

        </div>

    <?php ob_end_flush(); ?>
    
</body>
    
</html>
