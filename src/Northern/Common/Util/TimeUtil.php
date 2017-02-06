<?php
/*!
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Northern\Common\Util;

class TimeUtil
{
    /**
     * Returns the time in seconds rounded down to the last whole minute. E.g:
     * 12h:34m:23s becomes 12h:34m:00s.
     *
     * @param  int $time
     * @return int
     */
    public static function getByMinute($time)
    {
        return floor($time / 60) * 60;
    }

    /**
     * Returns the time in seconds rounded down to the next whole minute. E.g:
     * 12h:34m:23s becomes 12h:35m:00s.
     *
     * @param  int $time
     * @return int
     */
    public static function getByNextMinute($time)
    {
        return TimeUtil::getByMinute($time) + 60;
    }

    /**
     * Returns the time in seconds rounded down to the next while minute. E.g:
     * 12h:34m:23s becomes 12h:33m:00s.
     *
     * @param  int $time
     * @return int
     */
    public static function getByPreviousMinute($time)
    {
        return TimeUtil::getByMinute($time) - 60;
    }

    /**
     * Returns the time in seconds rounded down to the last whole hour. E.g:
     * 12h:34m:23s becomes 12h:00m:00s.
     *
     * @param  int $time
     * @return int
     */
    public static function getByHour($time)
    {
        return floor($time / 60 / 60) * 60 * 60;
    }

    /**
     * Returns the time in seconds rounded down to the last whole hour. E.g:
     * 12h:34m:23s becomes 13h:00m:00s.
     *
     * @param  int $time
     * @param  int $hours
     * @return int
     */
    public static function getByNextHour($time, $hours = 1)
    {
        return TimeUtil::getByHour($time) + (60 * 60 * $hours);
    }

    /**
     * Returns the time in seconds rounded down to the last whole hour. E.g:
     * 12h:34m:23s becomes 11h:00m:00s.
     *
     * @param  int $time
     * @param  int $hours
     * @return int
     */
    public static function getByPreviousHour($time, $hours = 1)
    {
        return TimeUtil::getByHour($time) - (60 * 60 * $hours);
    }

    /**
     * Returns the time in seconds rounded down to the last whole day. E.g:
     * 2015/12/11 12h:34m:23s becomes 2015/12/11 00h:00m:00s.
     *
     * @param  int $time
     * @return int
     */
    public static function getByDay($time)
    {
        return mktime(0, 0, 0, date("n", $time), date("j", $time), date("Y", $time));
    }

    /**
     * Returns the time in seconds rounded down to the next whole day. E.g:
     * 2015/12/11 12h:34m:23s becomes 2015/12/12 00h:00m:00s.
     *
     * @param  int $time
     * @param $days int
     * @return int
     */
    public static function getByNextDay($time, $days = 1)
    {
        return mktime(0, 0, 0, date("n", $time), date("j", $time) + $days, date("Y", $time));
    }

    /**
     * Returns the time in seconds rounded down to the previous whole day. E.g:
     * 2015/12/11 12h:34m:23s becomes 2015/12/10 00h:00m:00s.
     *
     * @param  int $time
     * @param $days int
     * @return int
     */
    public static function getByPreviousDay($time, $days = 1)
    {
        return mktime(0, 0, 0, date("n", $time), date("j", $time) - $days, date("Y", $time));
    }

    /**
     * Returns the time in seconds rounded down to the last whole week (Sunday).
     * E.g: 2016/01/19 12h:34m:23s, which is a Tuesday, becomes 2016/01/11 00h:00m:00s,
     * which is the last Monday. Weeks start on Monday.
     *
     * @param  int $time
     * @return int
     */
    public static function getByWeek($time)
    {
        return strtotime("last monday", $time);
    }

    /**
     * Returns the time in seconds rounded down to the next whole day. E.g:
     * 2016/01/19, which is a Tuesday, becomes 2016/12/12 00h:00m:00s.
     *
     * @param  int $time
     * @param $weeks int
     * @return int
     */
    public static function getByNextWeek($time, $weeks = 1)
    {
        return strtotime("last monday +{$weeks} week");
    }

    /**
     *
     */
    public static function getByPreviousWeek($time, $weeks = 1)
    {
        return strtotime("last monday -{$weeks} week");
    }
}
