    <div class="projectRibbonStrip">
        
        <div class='col-xs-11'>

            <!-- create list of phases -->

            <?php

            $objProjects = new projects_ctl();
            $dsPhases = $objProjects->getProjectPhase();
            $dsCompanies = $objProjects->getCompaniesList();
            $dsStatusValues = $objProjects->getStatusList();

            $timeChecked = 0;
            $isAllChecked = '';
            $isOnTimeChecked = '';
            $isLateChecked = '';

            if (isset($_SESSION['project_phase_filter'])) { $project_phase_filter = $_SESSION['project_phase_filter'] ; } else { $project_phase_filter = '';}  
            if (isset($_SESSION['project_company_filter'])) { $project_company_filter = $_SESSION['project_company_filter'] ; } else { $project_company_filter = '';}  
            if (isset($_SESSION['project_status_filter'])) { $project_status_filter = $_SESSION['project_status_filter'] ; } else { $project_status_filter = '';}  

            if (isset($_SESSION['lateness_to_display'])) { $timeChecked = $_SESSION['lateness_to_display'] ; } 

            switch ($timeChecked) {
                case 0:
                    $isAllChecked = 'checked';
                    break;
                case 1:
                    $isOnTimeChecked = 'checked';
                    break;
                case 2:
                    $isLateChecked = 'checked';
                    break;
                default:
                    $isAllChecked = 'checked';
                    break;
            }

            ?>

            <!-- START OF COMPANY -->

            <span class='col-xs-3 projectRibbon floatLeft'>Company:<br>

                <select id='qryCompanyFilter' name='qryCompanyFilter' onchange='applyCompanyFilter()'>
                <option></option>

                <?php 

                    while ($dsCompany = $dsCompanies->fetch(PDO::FETCH_ASSOC)) {
                        if ($project_company_filter == $dsCompany['company_id']) {
                            echo "<option name='qryProjectCompany' value='" . $dsCompany['company_id'] . "' selected>" . $dsCompany['company_name'] ."</option>";
                        } else {
                            echo "<option name='qryProjectCompany' value='" . $dsCompany['company_id'] . "' >" . $dsCompany['company_name'] ."</option>";
                        }
                    }
                ?>

                </select>

            </span>

            <!-- END OF COMPANY -->

            <!-- START OF PHASE  -->

            <span class='col-xs-3 projectRibbon floatLeft'>Phase:<br>
            
                <select id='qryProjectPhaseFilter' name='qryProjectPhaseFilter' onchange='applyProjectPhaseFilter()'>
                <option></option>

                <?php
                
                    while ($dsPhase = $dsPhases->fetch(PDO::FETCH_ASSOC)) {
                        if ($project_phase_filter == $dsPhase['phase_id']) {
                            echo "<option name=qryProjectPhase value='" . $dsPhase['phase_id'] . "' selected>" . $dsPhase['phase_label'] ."</option>";
                        } else {
                            echo "<option name=qryProjectPhase value='" . $dsPhase['phase_id'] . "' >" . $dsPhase['phase_label'] ."</option>";
                        }
                    }

                ?>

                </select>

            </span>

            <!-- END OF PHASE -->

            <!-- START OF STATUS -->

            <span class='col-xs-3 projectRibbon floatLeft'>Status:<br>

            <select id='qryProjectStatusFilter' name='qryProjectStatusFilter' onchange='applyProjectStatusFilter()'>
                <option></option>

                <?php
                
                    while ($dsStatus = $dsStatusValues->fetch(PDO::FETCH_ASSOC)) {
                        if ($project_status_filter == $dsStatus['project_status_id']) {
                            echo "<option name=qryProjectStatus value='" . $dsStatus['project_status_id'] . "' selected>" . $dsStatus['project_status_value'] ."</option>";
                        } else {
                            echo "<option name=qryProjectStatus value='" . $dsStatus['project_status_id'] . "' >" . $dsStatus['project_status_value'] ."</option>";
                        }
                    }

                ?>                

            </select>

            </span>

            <!-- END OF STATUS -->

            <!-- START OF LATE -->
            
            <span class='col-xs-3 projectRibbon floatRight'>

            <br>

                    <input type='radio' id='allProjects' name='late' value='allProjects' onclick='changeProjectTime(0)' <?= $isAllChecked; ?>> All
                    &nbsp;&nbsp;&nbsp;
                    <input type='radio' id='onTime' name='late' value='onTime' onclick='changeProjectTime(1)' <?= $isOnTimeChecked; ?>> On Time
                    &nbsp;&nbsp;&nbsp;
                    <input type='radio' id='lateProjects' name='late' value='lateProjects' onclick='changeProjectTime(2)' <?= $isLateChecked; ?>> Late Only

                </span>

            <!-- END OF LATE -->


        </div>

        <span class='col-xs-1 textRight'>

            <br>
            <button class='btnRibbon floatRight textRight' type='submit' name='btnClearProjectFilters' title='Click to clear all filters.'> X </button>

        </span>
                
    </div>


