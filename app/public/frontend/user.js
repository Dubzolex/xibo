class SessionUserManager {

    token = null
    data = { user: null, screens: [] }
    
    constructor(token = null) {
        this.token = token || localStorage.getItem("token")
    }

    //Recuperation des donnees utilisateur
    request = async () => {
        await Promise.all([this.requestUser(), this.requestScreens()]);
    }

    requestUser = async () => {
        try {
            const response = await fetch(`/src/api/account.php?action=user&token=${this.token}`)
            this.data["user"] = await response.json()
        
        } catch(e) {
            console.error(e)
        }
    }

    requestScreens = async () => {
        try {
            const response = await fetch(`/src/api/account.php?action=screen&token=${this.token}`)
            this.data["screens"] = await response.json()
        
        } catch(e) {
            console.error(e)
        }
    }

    //Redirection si un acces n'est pas autorise
    goToHome = () => {
        window.location.href = "/"
    }
    goToEditor = () => {
        window.location.href = "/editor/"
    }
    goToLogin = () => {
        window.location.href = "/login/"
    }
    goToAccount = () => {
        window.location.href = "/login/account/"
    }
    goToCredentialsPassword = () => {
        window.location.href = "/login/credentials/?action=password"
    }

    goToCredentialsName = () => {
        window.location.href = "/login/credentials/?action=name"
    }

    goToCredentialsOnBoarding = () => {
        window.location.href = "/login/credentials/?action=onboarding"
    }

    
    //Verification des permissions sur certaine page
    verifySession = () => {
        if(this.token === null) {
            this.goToLogin()
            return
        }

        this.request()

        if(this.data.user === null) {
            localStorage.removeItem("token")
            this.goToLogin()
            return
        }
    }

    verifySessionOnLoginPage = () => {
        if(this.token) {
            this.goToAccount()
        }
    }

    verifySessionOnMainPage = () => {
        if(this.token === null) {
            this.goToLogin()

        } else {
            this.goToHome()
        }
    }

    checkAccess = (allowedRole) => {
        this.verifySession()
        if(!allowedRole.includes(this.data.user.role)) {
            this.goToHome()
        }
    }

    checkRoleEditor = () => {
        this.checkAccess(["editor", "manager", "admin"])
    }

    checkRoleManager = () => {
        this.checkAccess(["manager", "admin"])
    }

    checkRoleAdmin = () => {
        this.checkAccess(["admin"])
    }

    verifyScreenAccess = (screenId) => {
        this.verifyRoleEditor()
        
        const hasScreen = this.data.screens.some(s => s.id === screenId);
        if (!hasScreen) {
            this.goToEditor();
        }
    }

    verifyCredentials = () => {
        this.verifySession()
        const { passwordChangedAt, name } = this.data.user

        if(passwordChangedAt === null) {
            if(name === null) {
                this.goToCredentialsOnBoarding()

            } else {
                this.goToCredentialsPassword()

            }
        } else {
            if(name === null) {
                this.goToCredentialsName()

            }
        }
    }
}