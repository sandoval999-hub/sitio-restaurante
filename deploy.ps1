Remove-Item -Path "C:\xampp\htdocs\pupuseria" -Recurse -Force -ErrorAction SilentlyContinue
New-Item -ItemType Directory -Force -Path "C:\xampp\htdocs\pupuseria" | Out-Null
Copy-Item -Path "c:\Users\Admin\Desktop\pupuseria\*" -Destination "C:\xampp\htdocs\pupuseria\" -Recurse -Force

Write-Host "Deployment to XAMPP htdocs completed successfully!"
