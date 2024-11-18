
param (
    [Parameter(mandatory)] $id,
    [Parameter(mandatory)] $username,
    [Parameter(mandatory)] $password,
    $resetpassword
)
$env:PSModulePath=''


$description_prefix = 'Enrolment Number: '
$description_prefix_new = '[NEW] Enrolment Number: '

write-output "Processing student: [$id] $username"


write-output "Retrieving user: $username"
$user = get-aduser $username -properties description
if( $? ){
    if($user.description.contains($description_prefix_new) -or $resetpassword ) {
        write-output "Student is new or reset password"

        if($password){
            write-output "Resetting password:"
            $user | Set-ADAccountPassword -reset -NewPassword (ConvertTo-SecureString -AsPlainText $password -Force)
        }
        if( !$? ){
            write-output " Failed to reset password"
        }

        if($user.description.contains($description_prefix_new)) {
            write-output "student is new, change description"
            $correct_description = "$description_prefix$($id)"
            $user | set-aduser -description $correct_description
        }
    }
    else {
        $passwordOK = (New-Object DirectoryServices.DirectoryEntry "",$username,$password).psbase.name -ne $null
        if(!$passwordOk){
            write-output "Student password mismatch. Resetting password"
            if($user.enabled) {
                $user | Set-ADAccountPassword -reset -NewPassword (ConvertTo-SecureString -AsPlainText $password -Force)
            }
        }
    }
}
else {
    write-output "didnt find account"
}
