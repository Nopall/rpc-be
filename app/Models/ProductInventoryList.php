<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInventoryList extends Model
{
    protected $table = "productInventoryLists";

    protected $dates = ['created_at', 'deletedAt'];

    protected $guarded = ['id'];

    protected $fillable = [
        'productInventoryId', 'productType', 'productId',
        'usageId', 'quantity', 'isApprovedOffice', 'userApproveOfficeId',
        'userApproveAdminId', 'isApprovedAdmin', 'userApproveOfficeAt', 'userApproveAdminAt',
        'reasonOffice', 'reasonAdmin', 'dateCondition', 'itemCondition','isAnyImage', 'userId', 'userUpdateId'
    ];
}
