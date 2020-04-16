<?php


namespace App\Http\Requests;


interface IPayloadRequest
{
    function getPayload(): array;
}
