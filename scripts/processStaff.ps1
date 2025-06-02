
param (
    [Parameter(Mandatory = $true)]
    [string] $username,
    [string[]] $departments = @(),
    [string[]] $classifications = @(),
    [string] $additionalData
)
$env:PSModulePath=''

$decoded = $additionalData | ConvertFrom-Json

Write-Output $decoded.firstName
Write-Output $decoded.lastName
Write-Output $decoded.position
Write-Output $decoded.house
Write-Output $decoded.classification
Write-Output $decoded.photocopierId
Write-Output $decoded.phone

Write-Output "Processing staff: [$($username)]"

Write-Output "Retrieving user: $($username)"
$user = Get-ADUser -Identity $($username) -Properties Description, MemberOf

if ($?) {
    Write-Output "Current groups:"
    $user.MemberOf | ForEach-Object {
        Write-Output $_
    }

    Write-Output "Proposed classifications:"
    $classifications | ForEach-Object {
        Write-Output $_
    }

   Write-Output "Proposed departments:"
    $departments | ForEach-Object {
        Write-Output $_
    }
} else {
    Write-Output "Didn't find account"
}
