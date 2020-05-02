import StoreModuleBase from "./StoreModuleBase";
import {dto, requests} from "../dto";

export default class UsersModule extends StoreModuleBase {
    profile(id: number): Promise<dto.UserProfileDto> {
        return this.client.get('users/' + id + '/profile').then(r => r.data)
    }

    setStatus(status: string): Promise<void> {
        return this.client.put('users/profile/status', {
            status
        }).then(r => r.data)
    }

    settings(): Promise<dto.UserSettingsDto> {
        return this.client.get('users/profile/settings').then(r => r.data)
    }

    usernameIsTaken(username: string): Promise<boolean> {
        return this.client.get('users/username-taken/' + username).then(r => r.data)
    }

    update(d: {req: requests.UpdateUser, id: number}): Promise<void> {
        return this.client.patch('users/' + d.id, d.req).then(r => r.data)
    }
}
