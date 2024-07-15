<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Ubuntu&display=swap" rel="stylesheet">
    <!-- CSS Style -->
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <div class="entry-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Entries</h3>
            <div class="btn-group dropdown mb-2">
                <button type="button" class="btn btn-dark dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                    Sort By
                </button>
                <ul class="dropdown-menu">
                    <?php
                    echo '
                    <li><a class="dropdown-item" href="?order_by=entry_date&order_dir=asc">entry date asc</a></li>
                    <li><a class="dropdown-item" href="?order_by=entry_date&order_dir=desc">entry date desc</a></li>
                    <li><a class="dropdown-item" href="?order_by=entry_id&order_dir=asc">oldest</a></li>
                    <li><a class="dropdown-item" href="?order_by=entry_id&order_dir=desc">newest</a></li>
                ';
                    ?>

                </ul>
            </div>

        </div>

        <?php
        // session_start();
        include('database.php');
        function displayEntries()
        {
            global $conn;

            $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'entry_id';
            $order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';
            $user_id = $_SESSION['user_id'];

            $sql = "SELECT entry_id, sentiment_id, entry_date, content FROM entries WHERE user_id = ? ORDER BY $order_by $order_dir";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $date = new DateTime($row['entry_date']);
                    $formattedDate = $date->format('F d, Y');
                    $content = base64_decode($row['content']);

                    // Get the EntryID
                    $entry_id = $row['entry_id'];
                    $_SESSION['entry_id'] = $entry_id;
                    $entry_id = $_SESSION['entry_id'];

                    // Remove HTML tags
                    $plainTextContent = strip_tags($content);

                    // Get a substring of the content
                    $shortContent = substr($plainTextContent, 0, 300) . '...';

                    // URL encode the parameters
                    $encodedDate = urlencode($formattedDate);
                    $encodedContent = urlencode($content);

                    // Check if entry_id exists in tags table
                    $stmt = $conn->prepare("SELECT * FROM tags WHERE entry_id = ?");
                    $stmt->bind_param("i", $entry_id);
                    $stmt->execute();
                    $tagExists = $stmt->get_result()->num_rows > 0;

                    // Check if entry_id exists in medias table
                    $stmt = $conn->prepare("SELECT * FROM medias WHERE entry_id = ?");
                    $stmt->bind_param("i", $entry_id);
                    $stmt->execute();
                    $mediaExists = $stmt->get_result()->num_rows > 0;

                    // Check if sentiment_id is not null
                    $sentimentExists = !is_null($row['sentiment_id']);

                    echo '
    <a id="myLink" href="home.php?entry=true&entry_id=' . $entry_id . '">
    <div class="card">
        <div class="card-body py-3 d-flex align-items-start">';
                    if ($sentimentExists) {
                        $sentiment_id = $row['sentiment_id'];
                        echo '<img src="images/text-editor-img/sentiments-svg/sentiments-colors/' . $sentiment_id . '.svg" class="sentiment responsive-img">';
                    }
                    echo '<div class="card-content mb-3">
                <div class="d-flex align-items-center card-details">
                    <h4>' . $formattedDate . '</h4>
                    <div class="d-flex align-items-center attachments" >';
                    if ($tagExists) {
                        echo '<img src="images/text-editor-img/tag-color.svg"  style="margin-bottom: 9px;">';
                    }
                    if ($mediaExists) {
                        echo '<img src="images/text-editor-img/photo.svg" style="margin-bottom: 9px;">';
                    }
                    echo '
                    </div>
                </div>
                <p>' . $shortContent . '</p>
            </div>
        </div>
    </div>
</a>
    ';
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        displayEntries();
        ?>

        <a id="myLink" href="home.php?entry=true">
            <div class="card">
                <div class="card-body py-3 d-flex align-items-start">
                    <!-- <img src="images/text-editor-img/sentiments-svg/sentiments-colors/5-happy.svg" class="sentiment responsive-img"> -->
                    <div class="card-content mb-3">
                        <div class="d-flex align-items-center card-details">
                            <h4>May 15, 2024</h4>
                            <div class="d-flex align-items-center attachments">
                                <img src="images/text-editor-img/tag.svg">
                                <img src="images/text-editor-img/media.svg">
                                <img src="images/text-editor-img/sentiment.svg">
                            </div>
                        </div>
                        <p> hakdogggggggg

                        </p>
                    </div>
                </div>
            </div>
        </a>

        <!-- <a id="myLink" href="home.php?entry=true">
            <div class="card">
                <div class="card-body py-3 d-flex align-items-start">
                    <img src="images/text-editor-img/sentiments-svg/sentiments-colors/5-happy.svg" class="sentiment responsive-img">
                    <div class="card-content mb-3">
                        <div class="d-flex align-items-center card-details">
                            <h4>May 15, 2024</h4>
                            <div class="d-flex align-items-center attachments">
                                <img src="images/text-editor-img/tag.svg">
                                <img src="images/text-editor-img/media.svg">
                                <img src="images/text-editor-img/sentiment.svg">
                            </div>
                        </div>
                        <p>

                        </p>
                    </div>
                </div>
            </div>
        </a> -->




    </div>







</body>



</html>