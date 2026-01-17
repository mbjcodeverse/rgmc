    <div class="sidebar sidebar-light sidebar-main sidebar-fixed sidebar-expand-md">
      <!-- Sidebar mobile toggler -->
      <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
          <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
          <i class="icon-screen-full"></i>
          <i class="icon-screen-normal"></i>
        </a>
      </div>
      <!-- /sidebar mobile toggler -->
      <!-- Sidebar content -->
      <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
          <div class="card-body">
            <div class="media">
              <div class="mr-3">
                <?php 
                  if ($_SESSION["photo"] != "") {
                    echo '<img src="'.$_SESSION["photo"].'"class="rounded-circle" height="38" alt="">';
                  }else{
                    echo '<img class="rounded-circle" height="38" alt="" src="views/img/users/default/anonymous.png">';
                  }
                ?>                

              </div>
              <div class="media-body">
                <?php
                  $table = 'employees';
                  $item = 'empid';
                  $value = $_SESSION["empid"];
                  $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
                  $employee_name = $employee["fname"].' '.$employee["lname"];
                ?>

                <div class="media-title font-weight-semibold"><?php echo $employee_name; ?></div>
                <div class="font-size-xs opacity-50">
                  <i class="icon-user font-size-sm"></i> &nbsp;<?php echo $_SESSION["utype"]; ?>
                </div>
              </div>
              <div class="ml-3 align-self-center">
                <a href="resetloginaccount" class="text-white"><i class="icon-cog3"></i></a>
              </div>
            </div>
          </div>
        </div>
        <!-- /user menu -->

        <?php if ($_SESSION["ulevel"] == 'Master' || $_SESSION["ulevel"] == 'Admin') { ?>
            <div class="ml-3 align-self-center">
                <a href="switchview" class="btn btn-outline bg-indigo-300 text-indigo-300 border-indigo-300" style="color:#e3e3e3;width:200px;">
                    <?php echo ($_SESSION["utype"] == 'Manufacturing') ? 'Switch to Technical' : 'Switch to Manufacturing'; ?>
                </a>
            </div>
        <?php } ?>

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
          <ul class="nav nav-sidebar" data-nav-type="accordion">
            <!-- Main -->
<!--             <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li> -->
            
            <?php    
                if($_SESSION["mmd"] != "Restricted"){
                  echo '      
                    <li class="nav-item">
                      <a href="factorydashboard" class="nav-link">
                        <i class="icon-home4"></i>
                        <span>
                          Dashboard
                        </span>
                      </a>
                    </li>';
                }else{
                  echo '      
                    <li class="nav-item">
                      <a href="default" class="nav-link"></a>
                    </li>';                  
                }

                if($_SESSION["tmd"] != "Restricted"){
                  echo '      
                    <li class="nav-item">
                      <a href="home" class="nav-link">
                        <i class="icon-home4"></i>
                        <span>
                          Dashboard
                        </span>
                      </a>
                    </li>';
                }else{
                  echo '      
                    <li class="nav-item">
                      <a href="default" class="nav-link"></a>
                    </li>';                  
                }
            ?>
            <!-- Transactions -->
            <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Transactions</div> <i class="icon-menu" title="Forms"></i></li>

            <?php    
                if(($_SESSION["tmt"] != "Restricted")||($_SESSION["tmi"] != "Restricted")){
                  echo '
                    <li class="nav-item nav-item-submenu">
                      <a href="#" class="nav-link"><i class="icon-hammer-wrench"></i> <span>Management</span></a>
                      <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        if($_SESSION["tmt"] != "Restricted"){     
                          echo '
                              <li class="nav-item"><a href="machinetracking" class="nav-link">Machine Tracking</a></li>  
                          ';
                        }

                        if($_SESSION["tmi"] != "Restricted"){     
                          echo '
                              <li class="nav-item"><a href="machineinspect" class="nav-link">Inspection</a></li>  
                          ';
                        }                      
                      echo '</ul>';
                    echo '</li>';
                }
            ?>

            <?php   
                if($_SESSION["mip"] != "Restricted"){           
                  echo '
                      <li class="nav-item"><a href="incomingrawmats" class="nav-link"><i class="icon-truck"></i> <span>Incoming Stocks</span></a></li>     
                  ';
                }
            ?>            

            <?php    
                if(($_SESSION["mfp"] != "Restricted")||($_SESSION["mpc"] != "Restricted")){
                  echo '
                    <li class="nav-item nav-item-submenu">
                      <a href="#" class="nav-link"><i class="icon-stack2"></i> <span>Production</span></a>
                      <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        if($_SESSION["mfp"] != "Restricted"){                      
                          echo '
                              <li class="nav-item"><a href="joborder" class="nav-link">Job Order</a></li>  
                          ';
                        }
                        // if($_SESSION["mfp"] != "Restricted"){                      
                        //   echo '
                        //       <li class="nav-item"><a href="prodfin" class="nav-link">Final Product Output [ + ]</a></li>  
                        //   ';
                        // }
                        if($_SESSION["mfp"] != "Restricted"){                      
                          echo '
                              <li class="nav-item"><a href="prodwaste" class="nav-link">Production/Waste Output [ + ]</a></li>  
                          ';
                        }                        
                        if($_SESSION["mpc"] != "Restricted"){
                          echo '
                              <li class="nav-item"><a href="prodcom" class="nav-link">Product Subcomponents [ + ]</a></li>  
                          ';      
                        }
                        
                        if($_SESSION["mmr"] != "Restricted"){
                          echo '
                             <li class="nav-item-divider"></li>  
                          ';
                        }                        

                        if($_SESSION["mmr"] != "Restricted"){
                          echo '
                              <li class="nav-item"><a href="recycle" class="nav-link">Material Recycling [ + ]</a></li>  
                          ';      
                        } 
                      echo '</ul>';
                    echo '</li>';
                }
            ?>

            <?php    
                if(($_SESSION["mrm"] != "Restricted")||($_SESSION["mmr"] != "Restricted")||($_SESSION["md"] != "Restricted")||($_SESSION["mret"] != "Restricted")){
                  echo '
                    <li class="nav-item nav-item-submenu">
                      <a href="#" class="nav-link"><i class="icon-cart-add2"></i> <span>Stock Control</span></a>
                      <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        if($_SESSION["mrm"] != "Restricted"){                      
                          echo '
                              <li class="nav-item"><a href="rawout" class="nav-link">Raw Material Requisition [ - ]</a></li>  
                          ';
                        } 

                        if(($_SESSION["mpc"] != "Restricted")||($_SESSION["mrm"] != "Restricted")){
                          echo '
                             <li class="nav-item-divider"></li>  
                          ';
                        } 

                        if($_SESSION["md"] != "Restricted"){                      
                          echo '
                              <li class="nav-item"><a href="debris" class="nav-link">Waste & Damages [ + ]</a></li>  
                          ';
                        }
                        // if($_SESSION["mret"] != "Restricted"){
                        //   echo '
                        //       <li class="nav-item"><a href="recycle" class="nav-link">Returns</a></li>  
                        //   ';      
                        // }  
                        if($_SESSION["mret"] != "Restricted"){
                          echo '
                              <li class="nav-item"><a href="excess" class="nav-link">Machine Excess Materials [ - ]</a></li>  
                          ';      
                        }        
                        
                        if($_SESSION["mret"] != "Restricted"){
                          echo '
                             <li class="nav-item-divider"></li>  
                          ';
                          echo '
                              <li class="nav-item"><a href="matreturn" class="nav-link">Materials Return [ + ]</a></li>  
                          ';      
                        } 
                      echo '</ul>';
                    echo '</li>';
                }
            ?>            

            <?php    
                if(($_SESSION["tpo"] != "Restricted")||($_SESSION["tis"] != "Restricted")){
                  echo '
                    <li class="nav-item nav-item-submenu">
                      <a href="#" class="nav-link"><i class="icon-stack2"></i> <span>Replenishment</span></a>
                      <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        if($_SESSION["tpo"] != "Restricted"){     
                          echo '
                              <li class="nav-item"><a href="purchaseorder" class="nav-link">Incoming Stocks</a></li>  
                          ';
                        }

                        // if($_SESSION["tis"] != "Restricted"){     
                        //   echo '
                        //       <li class="nav-item"><a href="incoming" class="nav-link">Incoming Stocks</a></li>  
                        //   ';
                        // }                         
                      echo '</ul>';
                    echo '</li>';
                }
            ?>

            <?php   
                if(($_SESSION["trel"] != "Restricted")||($_SESSION["tret"] != "Restricted")||($_SESSION["tadj"] != "Restricted")){ 
                  echo '
                    <li class="nav-item nav-item-submenu">
                      <a href="#" class="nav-link"><i class="icon-cart-add2"></i> <span>Stock Control</span></a>
                      <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        if($_SESSION["trel"] != "Restricted"){     
                          echo '
                              <li class="nav-item"><a href="stockout" class="nav-link">Releasing</a></li>  
                          ';
                        }

                        if($_SESSION["tret"] != "Restricted"){     
                          echo '
                              <li class="nav-item"><a href="return" class="nav-link">Return</a></li>  
                          ';
                        }

                        if($_SESSION["tadj"] != "Restricted"){     
                          echo '
                              <li class="nav-item"><a href="" class="nav-link">Adjustment</a></li>  
                          ';
                        }  
                      echo '</ul>';
                    echo '</li>';
                }
            ?>                        

            <?php
              if($_SESSION["tinv"] != "Restricted"){
                echo '
                  <li class="nav-item"><a href="inventory" class="nav-link"><i class="icon-calculator4"></i> <span>Physical Inventory</span></a></li>     
                ';                  
              }
            ?>

            <?php
              if($_SESSION["trep"] != "Restricted"){     
                echo '
                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack"></i> <span>Reports</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                      // echo '
                      //       <li class="nav-item"><a href="" class="nav-link">Purchase Order</a></li>  
                      // ';
                      // echo '
                      //       <li class="nav-item"><a href="" class="nav-link">Incoming</a></li>  
                      // ';
                      echo '
                             <li class="nav-item"><a href="machinetrackingreport" class="nav-link">Machine Reliability Trends</a></li>  
                      ';
                      echo '
                             <li class="nav-item"><a href="purchasereport" class="nav-link">Incoming Stocks</a></li>  
                      ';  
                      // echo '
                      //        <li class="nav-item"><a href="incomingreport" class="nav-link">Incoming Stocks</a></li>  
                      // ';       
                      echo '
                             <li class="nav-item-divider"></li>  
                      ';    
                      echo '
                             <li class="nav-item"><a href="releasereport" class="nav-link">Item Releases</a></li>  
                      '; 
                      echo '
                             <li class="nav-item"><a href="returnreport" class="nav-link">Item Returns</a></li>  
                      '; 
                      echo '
                             <li class="nav-item-divider"></li>  
                      '; 
                      echo '
                             <li class="nav-item"><a href="itemreport" class="nav-link">Item Details</a></li>  
                      ';          
                    echo '</ul>';
                  echo '</li>';
              }
            ?>
            

            <?php    
                if(($_SESSION["mir"] != "Restricted")||($_SESSION["minv"] != "Restricted")){
                  echo '
                    <li class="nav-item nav-item-submenu">
                      <a href="#" class="nav-link"><i class="icon-calculator4"></i> <span>Physical Inventory</span></a>
                      <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        if($_SESSION["mir"] != "Restricted"){                      
                          echo '
                              <li class="nav-item"><a href="inventoryrawmats" class="nav-link">Raw Materials Inventory</a></li>  
                          ';
                        }
                        // if($_SESSION["minv"] != "Restricted"){
                        //   echo '
                        //       <li class="nav-item"><a href="inventoryproducts" class="nav-link">Products Inventory</a></li>  
                        //   ';      
                        // }                                                            
                      echo '</ul>';
                    echo '</li>';
                }
            ?>             
            
            <?php
              if($_SESSION["mrep"] != "Restricted"){     
                echo '
                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack"></i> <span>Reports</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                      echo '
                             <li class="nav-item"><a href="rawoutreport" class="nav-link">Raw Material Requisition [ - ]</a></li>  
                      ';

                      echo '
                             <li class="nav-item-divider"></li>  
                      '; 
                      
                      echo '
                             <li class="nav-item"><a href="prodfinreport" class="nav-link">Final Production Output [ + ]</a></li>  
                      ';
                      
                      echo '
                             <li class="nav-item"><a href="prodcomreport" class="nav-link">Subcomponents Production [ + ]</a></li>  
                      ';                      

                      echo '
                             <li class="nav-item"><a href="recyclereport" class="nav-link">Materials Recycling [ + ]</a></li>  
                      ';                      
                      
                      echo '
                             <li class="nav-item-divider"></li>  
                      ';    
                      
                      echo '
                             <li class="nav-item"><a href="debrisreport" class="nav-link">Waste & Damages [ + ]</a></li>  
                      ';      
                      
                      echo '
                             <li class="nav-item-divider"></li>  
                      ';    

                      echo '
                             <li class="nav-item"><a href="excessreport" class="nav-link">Machine Excess Materials [ - ]</a></li>  
                      ';  
                      
                      echo '
                             <li class="nav-item"><a href="matreturnreport" class="nav-link">Material Returns [ + ]</a></li>  
                      ';    
                      
                      echo '
                             <li class="nav-item-divider"></li>  
                      ';    

                      echo '
                             <li class="nav-item"><a href="quotareport" class="nav-link">Quota Compliance</a></li>  
                      '; 
                    echo '</ul>';
                  echo '</li>';
              }
            ?>            

            <?php
                if($_SESSION["psup"] != "Restricted" || $_SESSION["pemp"] != "Restricted"){     
                echo '
                  <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Documents</div> <i class="icon-menu" title="Forms"></i></li>

                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-folder-open3"></i> <span>Profile</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';

                      if($_SESSION["psup"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="supplier" class="nav-link">Supplier</a></li>  
                        ';
                      }                                            

                      if($_SESSION["pemp"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="employees" class="nav-link">Employees</a></li>  
                        ';
                      }

                      if($_SESSION["tmac"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="building" class="nav-link">Building</a></li>  
                        ';
                      }                      
                    echo '</ul>';
                  echo '</li>';
                }

                if($_SESSION["tprt"] != "Restricted" || $_SESSION["tcat"] != "Restricted" || $_SESSION["tbr"] != "Restricted" || $_SESSION["mirm"] != "Restricted" || $_SESSION["mifp"] != "Restricted"){
                  echo '                  
                   <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-price-tags2"></i> <span>Items</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';

                      if($_SESSION["tprt"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="items" class="nav-link">Parts</a></li>  
                        ';
                      }

                      if($_SESSION["tcat"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="category" class="nav-link">Category</a></li>  
                        ';
                      }

                      if($_SESSION["tbr"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="brand" class="nav-link">Brand</a></li>  
                        ';
                      } 
                      
                      if($_SESSION["mirm"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="rawmats" class="nav-link">Raw Materials</a></li>  
                        ';
                      }

                      if($_SESSION["mifp"] != "Restricted"){     
                        echo '
                             <li class="nav-item"><a href="finishedgoods" class="nav-link">Finished Goods</a></li>  
                        ';
                      }
                      
                      if($_SESSION["mifp"] != "Restricted"){     
                        echo '
                            <li class="nav-item"><a href="prodcapacity" class="nav-link">Production Capacity Tracker</a></li>  
                        ';
                      }

                      if($_SESSION["mcrm"] != "Restricted" || $_SESSION["mcfg"]){
                        echo '
                          <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link"><span>Categories</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                              if($_SESSION["mcrm"] != "Restricted"){     
                                echo '
                                    <li class="nav-item"><a href="categoryrawmats" class="nav-link">Raw Materials</a></li>  
                                ';
                              }

                              if($_SESSION["mcfg"] != "Restricted"){     
                                echo '
                                    <li class="nav-item"><a href="categorygoods" class="nav-link">Finished Goods</a></li>  
                                ';
                              }                      
                            echo '</ul>';
                          echo '</li>';
                      }

                    echo '</ul>';
                  echo '</li>';
              }
            ?>

            <?php
                if($_SESSION["tmac"] != "Restricted" || $_SESSION["tcls"] != "Restricted"){
                  echo '                  
                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-cog3"></i> <span>Equipment</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';

                      if($_SESSION["tmac"] != "Restricted"){     
                        echo '
                            <li class="nav-item"><a href="machine" class="nav-link">Machines</a></li>  
                        ';
                      }                    

                      if($_SESSION["tcls"] != "Restricted"){     
                        echo '
                            <li class="nav-item"><a href="classification" class="nav-link">Classification</a></li>  
                        ';
                      }                      
                    echo '</ul>';
                  echo '</li>';
                }
            ?>

            <?php
                if($_SESSION["plog"] != "Restricted"){     
                  echo '
                        <li class="nav-item"><a href="loginreport" class="nav-link"><i class="icon-users4"></i> <span>Login Tracker</span></a></li>  
                  ';
                }    
            ?>

            <!-- Other Cost -->
            <?php
              if($_SESSION["pcost"] != "Restricted"){    
                echo '                  
                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-cog3"></i> <span>Other Cost</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                        echo '
                            <li class="nav-item"><a href="othercost" class="nav-link">Consolidated Cost</a></li>  
                        ';
    
                        echo '
                            <li class="nav-item"><a href="prodmetrics" class="nav-link">Production Metrics</a></li>  
                        ';
                    echo '</ul>';
                  echo '</li>';
                
                

                // echo '
                //   <li class="nav-item"><a href="othercost" class="nav-link"><i class="icon-key"></i> <span>Other Cost</span></a></li>     
                // ';
              }
            ?>            

            <!-- Access Privilege -->
            <?php
              if($_SESSION["paccess"] != "Restricted"){     
                echo '
                  <li class="nav-item"><a href="access" class="nav-link"><i class="icon-key"></i> <span>Access Privilege</span></a></li>     
                ';
              }
            ?>
          </ul>
        </div>
        <!-- /main navigation -->
      </div>
      <!-- /sidebar content -->
    </div>

<script src="views/js/sidebar.js"></script>    