export type UpdatePayload<T, TId = number> = {
    id: TId,
    data: T
}
