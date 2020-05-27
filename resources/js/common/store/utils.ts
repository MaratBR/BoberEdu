export type UpdatePayload<T, TId = number> = {
    id: TId,
    data: T
}

export type DeletePayload<TId = number> = {
    id: TId,
    reason: string
}
