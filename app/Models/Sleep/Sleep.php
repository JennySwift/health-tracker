<?php

namespace App\Models\Sleep;

use App\Traits\Models\Relationships\OwnedByUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sleep
 * @package App\Models\Sleep
 */
class Sleep extends Model
{
    use OwnedByUser;

    /**
     * @var string
     */
    protected $table = 'sleep';

    /**
     * @var array
     */
    protected $fillable = ['start', 'finish'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *
     * @param bool $fakeStart
     * @return int
     */
    public function getStartRelativeHeight($fakeStart = false)
    {
        if ($fakeStart) {
            return 0;
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->start)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $this->start)->hour(0)->minute(0));
    }

    /**
     *
     * @return int
     */
    public function getFinishRelativeHeight()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->finish)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $this->finish)->hour(0)->minute(0));
    }

    /**
     *
     * @param bool $nullValue
     * @return int
     */
    public function getDurationInMinutes($nullValue = false)
    {
        if (!$nullValue) {
            //Start and finish times are on the same day
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
            return $finish->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $this->start));
        }
        else if ($nullValue === 'start') {
            //The entry was finished on one day and started on an earlier day,
            //so calculate the time from the finish till the most recent midnight
            $finish = Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
            $midnight = clone $finish;
            $midnight = $midnight->hour(0)->minute(0);
            return $finish->diffInMinutes($midnight);
        }
        else if ($nullValue === 'finish') {
            //The entry was started on one day and finished on a later day,
            //so calculate the time from the start till midnight
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->start);
            $midnight = clone $start;
            $midnight = $midnight->hour(24)->minute(0);
            return $start->diffInMinutes($midnight);
        }

    }

    /**
     *
     * @return static
     */
    public function getFinish()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->finish);
    }
}
