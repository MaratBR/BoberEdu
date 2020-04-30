import StoreModuleBase from "./StoreModuleBase";
import {dto} from "../dto";

export default class UsersModule extends StoreModuleBase {
    profile(id: number): Promise<dto.UserProfileDto> {
        return this.client.get('users/' + id + '/profile').then(r => r.data)
    }

    setStatus(status: string): Promise<void> {
        return this.client.put('users/profile/status', {
            status
        }).then(r => r.data)
    }
}
