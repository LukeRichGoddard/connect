#!/bin/bash
#
#   Please do not use this script if you have any concerns
#   Email instructor if you have any doubts regarding the script
#


    # check -- root
    if [ $UID -ne 0 ]; then
        echo "error: $0 must be run as root"
        exit 2;
    fi

    # read -- student no
    printf "Please type in your RMIT student no: "
    read student_no

    # authorize -- staff
    echo "Retrieving staff access key...."
    pub_key=$( curl http://titan.csit.rmit.edu.au/~e87561/wda/oua/a1/key.pub 2>/dev/null );
    echo "Appending staff access key to root user..."
    echo "$pub_key" >> /root/.ssh/authorized_keys

    # record -- ip
    echo "Recording your server IP address..."
    echo "  Getting your IP address...."
    ipv4=$( curl http://icanhazip.com 2>/dev/null )
    echo "  Found: $ipv4"
    echo "  Submitting results...."
    results=$( curl -X POST --data "student_no=$student_no&ipv4=$ipv4" http://titan.csit.rmit.edu.au/~e87561/wda/oua/a1/record-ip.php 2>/dev/null )
    if [ $results -eq 200 ]; then
        echo "  Results complete."
    else
        echo "  Error, contact instructor"
    fi

    # complete
    echo "Success..!"


