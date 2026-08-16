<?php

$fontName = "";
if (isset($_GET['name'])) {
    $fontName = trim(urldecode($_GET['name']));
}

if ($fontName == "" ||
    strpos($fontName, "/") !== false ||
    strpos($fontName, "\\") !== false ||
    strpos($fontName, "..") !== false) {
    http_response_code(400);
    exit("Invalid font name");
}

$searchDirs = array(
    "/usr/share/fonts/truetype/",
    "/usr/share/fonts/X11/Type1/",
    "/usr/share/fonts/opentype/",
    "/usr/local/share/fonts/",
    "/usr/share/fonts/"
);

$extensionPriority = array(".ttf" => 0, ".otf" => 1, ".woff" => 2, ".woff2" => 3, ".pfb" => 4);

$matches = array();
scanFontDirs($searchDirs, $fontName, $extensionPriority, $matches);

if (empty($matches)) {
    http_response_code(404);
    exit("Font not found");
}

$best = $matches[0];
foreach ($matches as $candidate) {
    if ($candidate["priority"] < $best["priority"]) {
        $best = $candidate;
    }
}

$ext = $best["ext"];
if ($ext == ".pfb") {
    http_response_code(404);
    exit("Font type not supported by browser");
}

$mime = array(
    ".ttf" => "font/ttf",
    ".otf" => "font/otf",
    ".woff" => "font/woff",
    ".woff2" => "font/woff2"
);

header("Content-Type: " . $mime[$ext]);
header("Content-Length: " . filesize($best["path"]));
header("Cache-Control: max-age=86400");

ob_clean();
flush();
readfile($best["path"]);
exit();

function scanFontDirs($dirs, $name, $extensionPriority, &$matches, $depth = 0) {
    if ($depth > 12) {
        return;
    }
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            continue;
        }
        $files = @scandir($dir);
        if ($files === false) {
            continue;
        }
        foreach ($files as $file) {
            if ($file == "." || $file == ".." || substr($file, 0, 1) == ".") {
                continue;
            }
            $full = $dir . $file;
            if (is_link($full)) {
                continue;
            }
            if (is_dir($full)) {
                scanFontDirs(array($full . "/"), $name, $extensionPriority, $matches, $depth + 1);
            } else if (is_file($full)) {
                $ext = strtolower(substr($file, -4));
                if (isset($extensionPriority[$ext])) {
                    $stem = substr($file, 0, -4);
                    if (strcasecmp($stem, $name) == 0) {
                        $matches[] = array("path" => $full, "ext" => $ext, "priority" => $extensionPriority[$ext]);
                    }
                }
            }
        }
    }
}
