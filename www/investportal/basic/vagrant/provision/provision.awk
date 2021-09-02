###
# Modifying Yii2's files for Vagrant VM
#
# @author HA3IK <golubha3ik@gmail.com>
# @version 1.0.0

BEGIN {
    print "AWK BEGINs its work:"
    IGNORECASE = 1

    # Correct IP - wildcard last octet
    match(ip, /(([0-9]+\.)+)/, arr)
    ip = arr[1] "*"
}
# BODY
{
    # Check if it's the same file
    if (FILENAME != isFile["same"]){
        msg = "- Work with: " FILENAME
        # Close a previous file
        close(isFile["same"])
        # Delete previous data
        delete isFile
        # Save current file
        isFile["same"] = FILENAME
        # Define array index for the file
        switch (FILENAME){
        case /config\/web\.php$/:
            isFile["IsConfWeb"] = 1
            msg = msg " - add allowed IP: " ip
            break
        }
        # Print the concatenated message for the file
        print msg
    }

    # IF config/web.php
    if (isFile["IsConfWeb"]){
        # IF line has "allowedIPs" and doesn't has our IP
        if (match($0, "allowedIPs") && !match($0, ip)){
            match($0, /([^\]]+)(.+)/, arr)
            $0 = sprintf("%s, '%s'%s", arr[1], ip, arr[2])
        }
        # Rewrite the file
        print $0 > FILENAME
    }
}
END {
    print "AWK ENDs its work."
}
