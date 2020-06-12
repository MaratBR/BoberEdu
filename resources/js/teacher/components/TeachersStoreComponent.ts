import {StoreComponent} from "@common/components/utils";
import {getTeacherModule} from "@app/teacher/store";

export default class TeachersStoreComponent extends StoreComponent {
    protected teacher = getTeacherModule()
}
