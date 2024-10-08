<?php
include "../../../Connections/Include.php";
include "../components/index.php";

if (isset($_POST['userid']) && isset($_POST['type'])) {
    $userid = $_POST['userid'];
    $type = $_POST['type'];

    switch ($type) {
        case 'personal':
            $query = "SELECT * FROM user WHERE userid = '$userid'";
            $result = mysqli_query($conn, $query);

            if ($rows = mysqli_fetch_array($result)) {
                // Format the user data as HTML for personal info
                echo '
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">User ID</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($userid) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Full Name</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['firstname'] . ' ' . ($rows['middlename'] ? $rows['middlename'] . ' ' : '') . $rows['lastname']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Gender</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['gender']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Qualifier</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="qualifier" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['qualifier']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="status" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['status']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Date of Birth</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("dateOfBirth", $rows) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Place of Birth</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['placeOfBirth']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Contact Number</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="contactNumber" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['contactNumber']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Weight</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="weight" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['weight']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Height</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="height" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['height']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Blood Type</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="bloodType" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['bloodType']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">GSIS</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="GSIS" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['GSIS']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Pag-IBIG</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="Pagibig" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['Pagibig']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">PhilHealth</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="Philhealth" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['Philhealth']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">SSS</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="SSS" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['SSS']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">TIN</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="TIN" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['TIN']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">PNPID</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="user" data-field="PNPID" data-id="' . $rows['infoid'] . '">' . htmlspecialchars($rows['PNPID']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                            <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                        <div class="flex w-0 flex-1 items-center">
                                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                <span class="truncate font-medium">resume_back_end_developer.pdf</span>
                                                <span class="flex-shrink-0 text-gray-400">2.4mb</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                        </div>
                                    </li>
                                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                        <div class="flex w-0 flex-1 items-center">
                                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                <span class="truncate font-medium">coverletter_back_end_developer.pdf</span>
                                                <span class="flex-shrink-0 text-gray-400">4.5mb</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                        </div>
                                    </li>
                                </ul>
                            </dd>
                        </div>
                    </dl>';
            } else {
                echo '<dl class="divide-y divide-gray-100">
                            <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No users information found</dd>
                  </dl>';
            }
            break;
        case 'address':
            $query = "SELECT * FROM useraddress WHERE userid = '$userid'";
            $result = mysqli_query($conn, $query);

            if ($rows = mysqli_fetch_array($result)) {
                // Format the address data as HTML
                echo '
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">House No.</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['houseno']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Barangay</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['barangay']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">City</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['city']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Provice</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['province']) . '</dd>
                        </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Region</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['region']) . '</dd>
                        </div>
                    </dl>';
            } else {
                echo '<dl class="divide-y divide-gray-100">
                            <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No users address found</dd>
                  </dl>';
            }
            break;
        case 'family':
            $query = "SELECT * FROM familybackground WHERE userid = '$userid'";
            $result = mysqli_query($conn, $query);

            $child = "SELECT * FROM children WHERE userid = '$userid'";
            $res = mysqli_query($conn, $child);

            if ($rows = mysqli_fetch_array($result)) {
                // Format the address data as HTML
                echo '
                        <dl class="divide-y divide-gray-100">
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Fathers Name</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['fatherFname'] . ' ' . ($rows['fatherMname'] ? $rows['fatherMname'] . ' ' : '') . $rows['fatherSrname']) . ' ' .  $rows['fatherExtension'] . '</dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Mothers Name</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['motherFname'] . ' ' . ($rows['motherMname'] ? $rows['motherMname'] . ' ' : '') . $rows['motherSrname']) . $rows['motherExtension'] . '</dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Spouse Name</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['spouseFname'] . ' ' . ($rows['spouseMdname'] ? $rows['spouseMdname'] . ' ' : '') . $rows['spouseSrname']) . $rows['spouseExtension'] . '</dd>
                            </div>
                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Children</dt>
                            </div>';


                if (mysqli_num_rows($res) > 0) {
                    while ($rows1 = mysqli_fetch_array($res)) {
                        echo '  <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Name</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows1['fullname']) . '</dd>
                            </div>
                            <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Date of Birth</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' .  emptyData('dateOfBirth', $rows1) . '</dd>
                            </div>
                            ';
                    }
                } else {
                    echo '<div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No children</dd>
                            </div>';
                }

                echo '</dl>';
            } else {
                echo '<dl class="divide-y divide-gray-100">
                                <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No users address found</dd>
                      </dl>';
            }
            break;
        case 'education':
            $query = "SELECT * FROM educationalbackground WHERE userid = '$userid' ORDER BY yearEnded DESC";
            $result = mysqli_query($conn, $query);

            if (!empty($result)) {
                // Format the address data as HTML
                while ($rows = mysqli_fetch_array($result)) {
                    echo '
                            <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Level</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['level']) . '</dd>
                                </div>
                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Name of School</dt>
                                    <dd class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['nameOfSchool']) . '</dd>
                                </div>
                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Degree/Course</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['degree']) . '</dd>
                                </div>
                                 <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Awards Received</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['awardReceived']) . '</dd>
                                </div>
                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Year Started</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("yearStarted", $rows) . '</dd>
                                </div>
                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Year Started</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("yearEnded", $rows) . '</dd>
                                </div>
                            </dl>';
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                                    <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No educations found</dd>
                          </dl>';
            }
            break;

        case 'eligibility':
            $query = "SELECT * FROM eligibility WHERE userid = '$userid' ORDER BY dateOfExam DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Format the address data as HTML
                while ($rows = mysqli_fetch_array($result)) {
                    echo '
                                <dl class="divide-y divide-gray-100">
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dd class="mt-1 text-base leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-medium ">' . htmlspecialchars($rows['eligibility']) . '</dd>
                                    </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">License No.</dt>
                                            <dd class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['licenseNo']) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Rating</dt>
                                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['rating']) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Place of Exam</dt>
                                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . htmlspecialchars($rows['placeOfExam']) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Date of Exam</dt>
                                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("dateOfExam", $rows) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Validity</dt>
                                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("validity", $rows) . '</dd>
                                        </div>
                                </dl>';
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                                        <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No eligibilities found</dd>
                              </dl>';
            }
            break;
        case 'service':
            $query = "SELECT * FROM service WHERE userid = '$userid'";
            $result = mysqli_query($conn, $query);
            $rows = mysqli_fetch_array($result);

            $query2 = "SELECT * FROM servicehistory WHERE serviceid = '$rows[serviceid]'";
            $result2 = mysqli_query($conn, $query2);
            $rows2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);
            $lastDatePromotion = end($rows2);

            echo '<div class="flex justify-content-end px-4 mt-3" data-bs-toggle="modal" data-bs-target="#addOrder">
                    <i class="fa-solid fa-bars fa-add cursor-pointer p-2 rounded-full bg-gray-200"></i>
                </div>';
            echo '  
            <form action="" method="POST" id="save-detail-orders" enctype="multipart/form-data">
                <div class="modal fade" id="addOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-6 font-semibold ml-2" id="staticBackdropLabel" data-table="service" >Promotion</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-[13px]">
                                <div class="row column-gap-4">
                                    <div class="col-7">
                                        <label for="newPosition" class="form-label">Position Title</label>
                                        <input type="text" id="newPosition" class="form-control" name="newPosition" required>
                                    </div>

                                    <div class="col">
                                        <label for="datePromotion" class="form-label">Promotion Date</label>
                                        <input type="date" id="datePromotion" class="form-control text-[13px]" name="datePromotion" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="serviceid" value="' .  $rows['serviceid'] . '">
                                <input type="hidden" name="userid" value="' .  $userid . '">
                                <button type="button" data-bs-dismiss="modal" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                                <button type="submit" class="save-detail inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            ';

            echo '
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Date Entered Service</dt>
                        <dd class="capitalize flat-pickr mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="service" data-date="authorityDate" data-field="entered" data-id="' . emptyData("serviceid", $rows) . '">' . emptyData("entered", $rows) . '</dd>
                    </div>
                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Appointment Status</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="service" data-field="appStatus" data-id="' . emptyData("serviceid", $rows) . '">' . emptyData("appStatus", $rows) . '</dd>
                    </div>
                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Date of Permanency</dt>
                        <dd class="mt-1 text-sm flat-pickrEd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="service" data-date="authorityDate" data-field="permanency" data-id="' . emptyData("serviceid", $rows) . '">' . emptyData("permanency", $rows) . '</dd>
                    </div>
                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Date of Last Promotion</dt>
                        <dd class="mt-1 text-sm flat-pickrSd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="service" data-date="authorityDate" data-field="lastPromotion" data-id="' . emptyData("serviceid", $rows) . '">' . date('F d, Y', strtotime($lastDatePromotion['datePromotion'])) . '</dd>
                    </div>
                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Step Increment</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="service" data-field="stepIncrement" data-id="' . emptyData("serviceid", $rows) . '" >' . emptyData("stepIncrement", $rows) . '</dd>
                    </div>
                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Date of Last Step Increment</dt>
                        <dd class="mt-1 text-sm flat-pickrAd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="service" data-date="authorityDate" data-field="lastStepIncrement" data-id="' . emptyData("serviceid", $rows) . '">' . emptyData("lastStepIncrement", $rows) . '</dd>
                    </div>
                </dl>

                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0 mt-4">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Promotion History</dt>
                </div>
                ';
            foreach ($rows2 as $data) {
                echo '
                <dl class="divide-y divide-gray-100">
                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Last Position</dt>
                        <dd class="capitalize flat-pickr mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" data-table="service" data-date="authorityDate" data-field="entered" data-id="">' . emptyData("lastPosition", $data) . '</dd>
                    </div>
                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">New Position</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" data-table="service" data-field="appStatus" data-id="">' . emptyData("newPosition", $data) . '</dd>
                    </div>
                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Date of Promotion</dt>
                        <dd class="mt-1 text-sm flat-pickrEd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" data-table="service" data-date="authorityDate" data-field="permanency" data-id="">' . emptyData("datePromotion", $data) . '</dd>
                    </div>
                </dl>';
            }
            break;
        case 'training':
            $query = "SELECT * FROM training WHERE userid = '$userid' ORDER BY dateEnd DESC";
            $result = mysqli_query($conn, $query);

            $dirName = selectName($conn, $userid);
            if (mysqli_num_rows($result) > 0) {
                // Format the address data as HTML
                while ($rows = mysqli_fetch_array($result)) {

                    $dir = "../../user/uploads/$dirName/$rows[uploadedDoc]";
                    $fileName = basename($dir); // Get the file name
                    $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                    $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                    echo '
                                    <dl class="divide-y divide-gray-100">
                                        <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dd class="mt-1 text-base leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-medium ">' . htmlspecialchars($rows['name']) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Date Start</dt>
                                            <dd class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("dateStart", $rows) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Date End</dt>
                                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("dateEnd", $rows) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Authority No</dt>
                                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="training" data-field="authorityNo" data-id="' . $rows['trainingid'] . '">' . emptyData("authorityNo", $rows) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Authority Date</dt>
                                            <dd class="mt-1 text-sm  flat-pickrAd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="training" data-date="authorityDate" data-id="' . $rows['trainingid'] . '">' . emptyData("authorityDate", $rows) . '</dd>
                                        </div>
                                        <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                            <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                                            <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                        <div class="flex w-0 flex-1 items-center">
                                                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                                            </svg>
                                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                                <span class="truncate font-medium">' .  htmlspecialchars($fileName) . ' </span>
                                                                <span class="flex-shrink-0 text-gray-400">' . $sizeFileFormatted . '</span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4 flex-shrink-0">
                                                            <a href="' . htmlspecialchars($dir) . '" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </dd>
                                        </div>
                                    </dl>';
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                            <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No trainings found</dd>
                    </dl>';
            }
            break;
        case 'leave':
            $query = "SELECT * FROM leaves WHERE userid = '$userid' ORDER BY dateEnd DESC";
            $result = mysqli_query($conn, $query);

            $dirName = selectName($conn, $userid);
            if (mysqli_num_rows($result) > 0) {
                // Format the address data as HTML
                while ($rows = mysqli_fetch_array($result)) {

                    $dir = "../../../HTML/user/uploads/$dirName/$rows[uploadedDoc]";
                    $fileName = basename($dir); // Get the file name
                    $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                    $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                    $statuses = [
                        'Pending',
                        'Approve',
                        'Reject',
                    ];

                    $options = '';

                    foreach ($statuses as $status) {
                        $selected = ($rows['status'] === $status) ? 'selected' : '';
                        $options .= "<option $selected value='$status'>$status</option>";
                    }

                    if ($rows['status'] == 'Pending') {
                        $stat = '<label class="form-label bg-yellow-100 text-sm rounded p-1 ml-4 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                    } elseif ($rows['status'] == 'Approve') {
                        $stat = '<label class="form-label bg-green-100 text-sm rounded p-1 ml-4 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                    } else {
                        $stat = '<label class="form-label bg-red-100 text-sm rounded p-1 ml-4 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                    }

                    echo '
                                        <dl class="divide-y divide-gray-100">
                                            <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dd class="mt-1 text-base leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-medium ">' . htmlspecialchars($rows['leaveType']) . $stat . '</dd>
                                            </div>
                                            <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt class="text-sm font-medium leading-6 text-gray-900">Date Start</dt>
                                                <dd class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("dateStart", $rows) . '</dd>
                                            </div>
                                            <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt class="text-sm font-medium leading-6 text-gray-900">Date End</dt>
                                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">' . emptyData("dateEnd", $rows) . '</dd>
                                            </div>
                                             <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt class="text-sm font-medium leading-6 text-gray-900">Authority No</dt>
                                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="leaves" data-field="authorityNo" data-id="' . $rows['leaveid'] . '">' . htmlspecialchars($rows['authorityNo']) . '</dd>
                                            </div>
                                            <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt class="text-sm font-medium leading-6 text-gray-900">Authority Date</dt>
                                                <dd class="mt-1 text-sm flat-pickrAd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="leaves" data-date="training" data-field="authorityDate" data-id="' . $rows['leaveid'] . '">' . emptyData("authorityDate", $rows) . '</dd>
                                            </div>
                                            <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> 
                                                   <select data-table="leaves" data-field="status" data-id="' . $rows['leaveid'] . '" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-xs sm:text-sm sm:leading-6 focus:outline focus:outline-offset-2 focus:outline-blue-500">
                                                       ' . $options . '
                                                    </select>
                                                </dd>
                                            </div>
                                            <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                                                <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                                    <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                                        <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                            <div class="flex w-0 flex-1 items-center">
                                                                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                                                </svg>
                                                                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                                    <span class="truncate font-medium">' .  htmlspecialchars($fileName) . ' </span>
                                                                    <span class="flex-shrink-0 text-gray-400">' . $sizeFileFormatted . '</span>
                                                                </div>
                                                            </div>
                                                            <div class="ml-4 flex-shrink-0">
                                                                <a href="' . htmlspecialchars($dir) . '" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </dd>
                                            </div>
                                        </dl>';
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                                <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No filed leaves found</dd>
                        </dl>';
            }
            break;
        case 'detail':
            $query = "SELECT * FROM detail WHERE userid = '$userid' ORDER BY dateEnd DESC";
            $result = mysqli_query($conn, $query);

            $dirName = selectName($conn, $userid);
            echo '<div class="flex justify-content-end px-4 mt-3" data-bs-toggle="modal" data-bs-target="#addOrder">
                    <i class="fa-solid fa-bars fa-add cursor-pointer p-2 rounded-full bg-gray-200"></i>
                </div>';
            echo '  
            <form action="" method="POST" id="save-detail-orders" enctype="multipart/form-data">
                <div class="modal fade" id="addOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-6 font-semibold ml-2" id="staticBackdropLabel" data-table="detail" >Detail Orders</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-[13px]">
                                <div class="row column-gap-4">
                                    <div class="col-8">
                                        <label for="orderType" class="form-label">Order Type</label>
                                        <input type="text" id="orderType" class="form-control" name="orderType" required>
                                    </div>
                                </div>

                                <div class="row column-gap-4 mt-3">
                                    <div class="col">
                                        <label for="dateStart" class="form-label">Date Start</label>
                                        <input type="date" id="dateStart" class="form-control text-[13px]" name="dateStart" required>
                                    </div>
                                    <div class="col">
                                        <label for="dateEnd" class="form-label">Date End</label>
                                        <input type="date" id="dateEnd" class="form-control  text-[13px]" name="dateEnd" required>
                                    </div>
                                </div>

                                <div class="row column-gap-4 mt-3">
                                    <div class="col-12">
                                        <label for="office" class="form-label">Unit/Office Station</label>
                                        <input type="text" id="office" class="form-control" name="office" required>
                                    </div>
                                </div>

                                <div class="row column-gap-4 mt-3">
                                    <div class="col">
                                        <label for="authNo" class="form-label">
                                            Authority Number
                                        </label>
                                        <input type="text" id="authNo" class="form-control" name="authNo" required>
                                    </div>
                                    <div class="col">
                                        <label for="authDate" class="form-label">Authority Date</label>
                                        <input type="date" id="authDate" class="form-control  text-[13px]" name="authDate" required>
                                    </div>
                                </div>

                                <div class="row column-gap-4 mt-3">
                                    <div class="col">
                                        <label for="formFile" class="form-label">Upload Document</label>
                                        <input class="form-control text-[13px]" name="file" type="file" id="formFile" accept=".docx,.jpeg,.jpg,.pdf,.xlsx,.png" required>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="userid" value="' .  $userid . '">
                                <button type="button" data-bs-dismiss="modal" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                                <button type="submit" class="save-detail inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            ';

            if (mysqli_num_rows($result) > 0) {
                // Format the address data as HTML
                while ($rows = mysqli_fetch_array($result)) {

                    $dir = "../../../HTML/user/uploads/$dirName/$rows[uploadedDoc]";
                    $fileName = basename($dir); // Get the file name
                    $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                    $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                    $statuses = [
                        'Pending',
                        'Approve',
                        'Reject',
                    ];

                    $options = '';

                    foreach ($statuses as $status) {
                        $selected = ($rows['status'] === $status) ? 'selected' : '';
                        $options .= "<option $selected value='$status'>$status</option>";
                    }

                    if ($rows['status'] == 'Pending') {
                        $stat = '<label class="form-label bg-yellow-100 text-sm rounded p-1 ml-4 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                    } elseif ($rows['status'] == 'Approve') {
                        $stat = '<label class="form-label bg-green-100 text-sm rounded p-1 ml-4 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                    } else {
                        $stat = '<label class="form-label bg-red-100 text-sm rounded p-1 ml-4 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                    }

                    echo '
                                            <dl class="divide-y divide-gray-100">
                                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dd class="mt-1 text-base leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-medium ">' . htmlspecialchars($rows['orderType']) . $stat . '</dd>
                                                </div>
                                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Date Start</dt>
                                                    <dd class="capitalize flat-pickrSd mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0  focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="detail" data-date="training" data-field="dateStart" data-id="' . $rows['detailid'] . '">' . emptyData("dateStart", $rows) . '</dd>
                                                </div>
                                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                     <dt class="text-sm font-medium leading-6 text-gray-900">Date End</dt>
                                                    <dd class="mt-1 text-sm flat-pickrEd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="detail" data-date="training" data-field="dateEnd" data-id="' . $rows['detailid'] . '">' . emptyData("dateEnd", $rows) . '</dd>
                                                
                                                </div>
                                                 <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Authority No</dt>
                                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="detail" data-field="authorityNo" data-id="' . $rows['detailid'] . '">' . htmlspecialchars($rows['authorityNo']) . '</dd>
                                                </div>
                                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Authority Date</dt>
                                                    <dd class="mt-1 text-sm flat-pickrAd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="detail" data-date="training" data-field="authorityDate" data-id="' . $rows['detailid'] . '">' . emptyData("authorityDate", $rows) . '</dd>
                                                </div>
                                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> 
                                                       <select data-table="detail" data-field="status" data-id="' . $rows['detailid'] . '" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-xs sm:text-sm sm:leading-6 focus:outline focus:outline-offset-2 focus:outline-blue-500">
                                                           ' . $options . '
                                                        </select>
                                                    </dd>
                                                </div>
                                                <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                                                    <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                                        <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                                <div class="flex w-0 flex-1 items-center">
                                                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                                        <span class="truncate font-medium">' .  htmlspecialchars($fileName) . ' </span>
                                                                        <span class="flex-shrink-0 text-gray-400">' . $sizeFileFormatted . '</span>
                                                                    </div>
                                                                </div>
                                                                <div class="ml-4 flex-shrink-0">
                                                                    <a href="' . htmlspecialchars($dir) . '" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </dd>
                                                </div>
                                            </dl>';
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                                    <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No detail orders record found</dd>
                            </dl>';
            }
            break;
        case 'criminal':
            $query = "SELECT * FROM `case` WHERE userid = '$userid' ORDER BY dateEnd DESC";
            $result = mysqli_query($conn, $query);

            $dirName = selectName($conn, $userid);
            echo '<div class="flex justify-content-end px-4 mt-3" data-bs-toggle="modal" data-bs-target="#addOrder">
                        <i class="fa-solid fa-bars fa-add cursor-pointer p-2 rounded-full bg-gray-200"></i>
                    </div>';
            echo '  
                <form action="" method="POST" id="save-detail-orders" enctype="multipart/form-data">
                    <div class="modal fade" id="addOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-6 font-semibold ml-2" id="staticBackdropLabel" data-table="case">Criminal Case</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 text-[13px]">
                                    <div class="row column-gap-4">
                                        <div class="col-8">
                                            <label for="orderType" class="form-label">Sunction</label>
                                            <select id="orderType" class="form-select text-[13px]" name="orderType" required>
                                                <option>Preventive Suspension</option>
                                                <option>Suspension</option>
                                                <option>Detention</option>
                                                <option>Termination</option>
                                            </select>
                                        </div>
                                    </div>
    
                                    <div class="row column-gap-4 mt-3">
                                        <div class="col">
                                            <label for="dateStart" class="form-label">Date Start</label>
                                            <input type="date" id="dateStart" class="form-control text-[13px]" name="dateStart" required>
                                        </div>
                                        <div class="col">
                                            <label for="dateEnd" class="form-label">Date End</label>
                                            <input type="date" id="dateEnd" class="form-control  text-[13px]" name="dateEnd" required>
                                        </div>
                                    </div>

                                    <div class="row column-gap-4 mt-3">
                                        <div class="col">
                                            <label for="authNo" class="form-label">
                                                Authority Number
                                            </label>
                                            <input type="text" id="authNo" class="form-control" name="authNo" required>
                                        </div>
                                        <div class="col">
                                            <label for="authDate" class="form-label">Authority Date</label>
                                            <input type="date" id="authDate" class="form-control  text-[13px]" name="authDate" required>
                                        </div>
                                    </div>

                                    <div class="row column-gap-4 mt-3">
                                        <div class="col">
                                            <label for="formFile" class="form-label">Upload Document</label>
                                            <input class="form-control text-[13px]" name="file" type="file" id="formFile" accept=".docx,.jpeg,.jpg,.pdf,.xlsx,.png" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="userid" value="' .  $userid . '">
                                    <button type="button" data-bs-dismiss="modal" class="cancel inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-300 sm:mt-0 sm:w-auto">Cancel</button>
                                    <button type="submit" class="save-detail inline-flex w-full ml-3 justify-center bg-green-600 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500 tranisition-all duration-200 sm:mt-0 sm:w-auto">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                ';

            if (mysqli_num_rows($result) > 0) {
                // Format the address data as HTML
                while ($rows = mysqli_fetch_array($result)) {

                    $dir = "../../../HTML/user/uploads/$dirName/$rows[uploadedDoc]";
                    $fileName = basename($dir); // Get the file name
                    $sizeFile = file_exists($dir) ? filesize($dir) : 0;
                    $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found';

                    $statuses = [
                        'Pending',
                        'Approve',
                        'Reject',
                    ];

                    $options = '';

                    foreach ($statuses as $status) {
                        $selected = ($rows['status'] === $status) ? 'selected' : '';
                        $options .= "<option $selected value='$status'>$status</option>";
                    }

                    if ($rows['status'] == 'Pending') {
                        $stat = '<label class="form-label bg-yellow-100 text-sm rounded p-1 ml-4 text-yellow-700 w-500 px-2 border-1 border-yellow-300">Pending</label>';
                    } elseif ($rows['status'] == 'Approve') {
                        $stat = '<label class="form-label bg-green-100 text-sm rounded p-1 ml-4 text-green-700 w-500 px-2 border-1 border-green-300">Approved</label>';
                    } else {
                        $stat = '<label class="form-label bg-red-100 text-sm rounded p-1 ml-4 text-red-700 w-500 px-2 border-1 border-red-300">Rejected</label>';
                    }

                    echo '
                                                <dl class="divide-y divide-gray-100">
                                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                        <dd class="mt-1 text-base leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-medium ">' . htmlspecialchars($rows['sunction']) . $stat . '</dd>
                                                    </div>
                                                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                        <dt class="text-sm font-medium leading-6 text-gray-900">Date Start</dt>
                                                        <dd class="capitalize flat-pickrSd mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0  focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="case" data-date="training" data-field="dateStart" data-id="' . $rows['caseid'] . '">' . emptyData("dateStart", $rows) . '</dd>
                                                    </div>
                                                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                         <dt class="text-sm font-medium leading-6 text-gray-900">Date End</dt>
                                                        <dd class="mt-1 text-sm flat-pickrEd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="case" data-date="training" data-field="dateEnd" data-id="' . $rows['caseid'] . '">' . emptyData("dateEnd", $rows) . '</dd>
                                                    
                                                    </div>
                                                     <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                        <dt class="text-sm font-medium leading-6 text-gray-900">Authority No</dt>
                                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="case" data-field="authorityNo" data-id="' . $rows['caseid'] . '">' . htmlspecialchars($rows['authorityNo']) . '</dd>
                                                    </div>
                                                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                        <dt class="text-sm font-medium leading-6 text-gray-900">Authority Date</dt>
                                                        <dd class="mt-1 text-sm flat-pickrAd leading-6 text-gray-700 sm:col-span-2 sm:mt-0 focus:outline focus:outline-offset-2 focus:outline-blue-500" contenteditable="true" data-table="case" data-date="training" data-field="authorityDate" data-id="' . $rows['caseid'] . '">' . emptyData("authorityDate", $rows) . '</dd>
                                                    </div>
                                                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                        <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> 
                                                           <select data-table="case" data-field="status" data-id="' . $rows['caseid'] . '" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-xs sm:text-sm sm:leading-6 focus:outline focus:outline-offset-2 focus:outline-blue-500">
                                                               ' . $options . '
                                                            </select>
                                                        </dd>
                                                    </div>
                                                    <div class="px-5 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                        <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                                                        <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                                            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                                                <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                                    <div class="flex w-0 flex-1 items-center">
                                                                        <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                                                        </svg>
                                                                        <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                                            <span class="truncate font-medium">' .  htmlspecialchars($fileName) . ' </span>
                                                                            <span class="flex-shrink-0 text-gray-400">' . $sizeFileFormatted . '</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-4 flex-shrink-0">
                                                                        <a href="' . htmlspecialchars($dir) . '" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </dd>
                                                    </div>
                                                </dl>';
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                                        <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No administrative/criminal case record found</dd>
                                </dl>';
            }
            break;
        case 'documents':

            $userDir = selectName($conn, $userid); // Get the user's directory
            $directory = "../../../HTML/user/uploads/$userDir";

            if (is_dir($directory)) {
                // Scan the directory for files
                $files = scandir($directory);

                // Filter out the current and parent directory references
                $files = array_diff($files, array('.', '..'));

                if (!empty($files)) {
                    foreach ($files as $file) {
                        $filePath = "$directory/$file"; // Full path to the file
                        $fileName = basename($file); // Corrected to get the file name
                        $fileType = getFileType($file);
                        $sizeFile = file_exists($filePath) ? filesize($filePath) : 0; // Get file type for each file
                        $sizeFileFormatted = $sizeFile > 0 ? formatSize($sizeFile) : 'File not found'; // Corrected to use the file path

                        // Render the appropriate HTML based on the file type
                        echo '<div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Attachments</dt>
                    <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium">' . htmlspecialchars($fileName) . ' </span>
                                        <span class="flex-shrink-0 text-gray-400">' . $sizeFileFormatted . '</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="' . htmlspecialchars($filePath) . '" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                </div>
                            </li>
                        </ul>
                    </dd>
                </div>';
                    }
                } else {
                    echo "<p class='text-center'>No files available.</p>";
                }
            } else {
                echo '<dl class="divide-y divide-gray-100">
                        <dd class="mt-1 text-center text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"> No documents uploaded found</dd>
                     </dl>';
            }

            break;
    }
} else {
    echo 'Invalid request.';
}
