<?php
include 'dbh.inc.php';
session_start();

if (!isset($_SESSION['userID']))
    header("Location: login.php");

if (isset($_GET['ChatRoomID'])) {

    echo "<h2>Memers of this chat: </h2>";


    $ChatroomID = mysqli_real_escape_string($conn, $_GET['ChatroomID']);

    //gets all the chatroom id's and names that the user is a part of
    $sqlGetMembers =
        "SELECT 
            _user.UserName AS 'UserName',
            _user.ID AS 'UserID',
            connector.Admin AS 'CurrentAdmin'
        FROM
            _user
        LEFT JOIN 
            connector ON _user.ID = connector.UserID
        WHERE 
            connector.ChatroomID = '$ChatRoomID';";

    $GetMembersResult = mysqli_query($conn, $sqlGetMembers);

    //checks if there was any users found connected to this chat
    if (mysqli_num_rows($GetMembersResult) > 0) {

        //loops through each recent message
        while ($ChatMembersRow = mysqli_fetch_assoc($GetMembersResult)) {

            $UserName = $ChatMembersRow['UserName'];
            $UserID = $ChatMembersRow['UserID'];
            $MemberAdmin = $ChatMembersRow['CurrentAdmin'];

            //outputs the  member
            echo "<div class='ConectedUser'>";

            //checks if the member being displayed is admin
            if ($MemberAdmin == 1) {

                echo "<p>" . $UserName . "#" . $UserID . " <a class='AlreadyAdmin' href='#'>Admin</a></p>";
            } else {

                //checks if the user is admin
                if ($adminStatus == 1)
                    echo "<p>" . $UserName . "#" . $UserID . "<a href='includes/zRemoveUser.php?UserToRemoveID=" . $UserID . "&ChatroomID=" . $ChatroomID . "'>Remove User</a><a href='#'>Make Admin</a></p>";
                else
                    echo "<p>" . $UserName . "#" . $UserID . "</p>";
            }
            echo "</div>";
        }
    }
}
