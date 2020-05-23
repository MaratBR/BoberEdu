import {AdminModule, getAdminModule} from "@admin/store/AdminModule";
import {StoreComponent} from "@common/components/utils";

export default class AdminStoreComponent extends StoreComponent {
    protected admin = getAdminModule();
}
