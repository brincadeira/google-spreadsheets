<?php
require_once __DIR__ . '/vendor/autoload.php';

/**
 * create an empty spreadsheet
 *
 */

function create($title)
{
    $googleAccountKeyFilePath = __DIR__ . '/credentials.json';
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $service = new Google_Service_Sheets($client);
    try
    {

        $spreadsheet = new Google_Service_Sheets_Spreadsheet(['properties' => ['title' => $title]]);
        $spreadsheet = $service
            ->spreadsheets
            ->create($spreadsheet, ['fields' => 'spreadsheetId']);
        printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
        return $spreadsheet->spreadsheetId;

    }
    catch(Exception $e)
    {
        // TODO(developer) - handle error appropriately
        echo 'Message: ' . $e->getMessage();
    }
}

/**
 * Write to a sheet
 *
 */
function updateValues($spreadsheetId, $range, $valueInputOption, $values)
{

    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
    $service = new Google_Service_Sheets($client);
    try
    {
        $body = new Google_Service_Sheets_ValueRange(['majorDimension' => 'COLUMNS', 'values' => $values]);
        $params = ['valueInputOption' => $valueInputOption];
        //executing the request
        $result = $service
            ->spreadsheets_values
            ->update($spreadsheetId, $range, $body, $params);
        printf("%d cells updated.", $result->getUpdatedCells());
        return $result;
    }
    catch(Exception $e)
    {
        // TODO(developer) - handle error appropriately
        echo 'Message: ' . $e->getMessage();
    }
}

$spreadsheetId = create('Topvisor Test Task');
if ($spreadsheetId) updateValues($spreadsheetId, 'A1:A10', 'USER_ENTERED', [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]]);
