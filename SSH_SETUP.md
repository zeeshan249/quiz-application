# GitHub SSH Setup (new laptop)

## 1. Generate a key

```powershell
ssh-keygen -t ed25519 -C "laptop-name" -f "$HOME\.ssh\id_ed25519" -N '""'
```

If it complains about the folder missing, create it first:

```powershell
New-Item -ItemType Directory -Force -Path "$HOME\.ssh"
```

## 2. Copy the public key

```powershell
Get-Content "$HOME\.ssh\id_ed25519.pub"
```

## 3. Add it to GitHub

Go to: github.com -> Settings -> SSH and GPG keys -> New SSH key -> paste the key.

## 4. Clone the repo over SSH

```powershell
git clone git@github.com:zeeshan249/quiz-application.git
```

If the repo already exists locally, set the remote instead:

```powershell
git remote set-url origin git@github.com:zeeshan249/quiz-application.git
```

---

Note: each laptop gets its own key, all authorized on the same account. If a laptop is lost or sold, delete just that key from GitHub - the rest keep working.

will update tommorow

git ssh url 
git remote set-url origin git@github.com:rezacodes-dev/quiz-application
.git
