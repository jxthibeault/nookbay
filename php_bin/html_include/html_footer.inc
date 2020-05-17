<?php
    /**
     * Writes a universal footer to the bottom of every HTML page
     * that calls this script.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1.2a
     */
    
    /**
     * Call function to write the footer.
     *
     * @author  Joshua Thibeault <jxthibeault@gmail.com>
     * @since   v0.1.2a
     *
     * @return  null
     */
    function writeFooter() {
        echo "<a href=\"https://nookbay.app/policy/privacy.php\">Privacy Policy</a>   ";
        echo "<a href=\"https://nookbay.app/policy/email_policy.php\">Email Policy</a>   ";
        echo "<a href=\"https://github.com/jxthibeault/nookbay/security/advisories?state=published\">Advisories</a>   ";
        echo "<a href=\"https://github.com/jxthibeault/nookbay/issues\">Issue Tracker</a>   ";
        echo "<a href=\"https://github.com/jxthibeault/nookbay\">Open Source</a>   ";
        echo "<br />v0.1.2a";
    }
    