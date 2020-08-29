export default class Auth {

    constructor(auth)
    {
        this.auth = auth;
    }

    can($ability)
    {
        return this.auth.abilities.includes($ability);
    }

    hasRole(roleName)
    {
        return this.auth.roles.includes(roleName);
    }

    id()
    {
        return this.auth.userId
    }
}
