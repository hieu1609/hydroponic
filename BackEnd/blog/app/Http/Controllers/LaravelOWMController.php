<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelOWM;
use App\User;

class LaravelOWMController extends BaseApiController
{
    /**
     * Response with the current weather of the requested location/city.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    /**
     * @SWG\Post(
     *     path="/weather/currentweather",
     *     description="get current weather",
     *     tags={"Weather"},
     *     summary="get current weather",
     *     security={{"jwt":{}}},
     *      @SWG\Parameter(
     *          name="body",
     *          description="get current weather",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="city",
     *                  type="string",
     *              )
     *          ),
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=403, description="Forbidden"),
     *      @SWG\Response(response=404, description="Not Found"),
     *      @SWG\Response(response=422, description="Unprocessable Entity"),
     *      @SWG\Response(response=500, description="Internal Server Error"),
     * )
     *
     */
    public function currentweather(Request $request)
    {
        try {
            $lowm = new LaravelOWM();
            $tz = new \DateTimeZone(config('app.timezone'));
            $city = $request->get('city');
            $coordinates = $request->get('coord');
            $lang = $request->get('lang', 'en');
            $units = $request->get('units', 'metric');

            if ($city === null && $coordinates == null) {
                $city = $request->user->city;
            }
            else {
                $user = $request->user;
                $user->update(['city' => $city]);
                $user->save();
            }

            $query = ($city) ?: $coordinates;

            $current_weather = $lowm->getCurrentWeather($query, $lang, $units, true);

            $data = [
                'city' => [
                    'id' => $current_weather->city->id,
                    'name' => $current_weather->city->name,
                    'lat' => $current_weather->city->lat,
                    'lon' => $current_weather->city->lon,
                    'country' => $current_weather->city->country,
                    'population' => $current_weather->city->population
                ],
                'sun' => [
                    'rise' => [
                        'date' => $current_weather->sun->rise->setTimezone($tz)->format('Y-m-d H:i:s'),
                        'timestamp' => $current_weather->sun->rise->setTimezone($tz)->getTimestamp()
                    ],
                    'set' => [
                        'date' => $current_weather->sun->set->setTimezone($tz)->format('Y-m-d H:i:s'),
                        'timestamp' => $current_weather->sun->set->setTimezone($tz)->getTimestamp()
                    ]
                ],
                'lastUpdate' => [
                    'date' => $current_weather->lastUpdate->setTimezone($tz)->format('Y-m-d H:i:s'),
                    'timestamp' => $current_weather->lastUpdate->setTimezone($tz)->getTimestamp()
                ]
            ];
            $data = array_merge($data, $this->parseData($current_weather));
            return $this->responseSuccess($data);
        } catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    /**
     * Response with the forecast of the requested location/city.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    /**
     * @SWG\Post(
     *     path="/weather/forecast",
     *     description="get forecast",
     *     tags={"Weather"},
     *     summary="get forecast",
     *     security={{"jwt":{}}},
     *      @SWG\Parameter(
     *          name="body",
     *          description="get forecast",
     *          required=true,
     *          in="body",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="city",
     *                  type="string",
     *              )
     *          ),
     *      ),
     *      @SWG\Response(response=200, description="Successful operation"),
     *      @SWG\Response(response=400, description="Bad request"),
     *      @SWG\Response(response=401, description="Unauthorized"),
     *      @SWG\Response(response=403, description="Forbidden"),
     *      @SWG\Response(response=404, description="Not Found"),
     *      @SWG\Response(response=422, description="Unprocessable Entity"),
     *      @SWG\Response(response=500, description="Internal Server Error"),
     * )
     *
     */
    public function forecast(Request $request)
    {
        try {
            $lowm = new LaravelOWM();
            $tz = new \DateTimeZone(config('app.timezone'));

            $city = $request->get('city');
            $coordinates = $request->get('coord');
            $lang = $request->get('lang', 'en');
            $units = $request->get('units', 'metric');
            $days = $request->get('days', 5);

            if ($city === null && $coordinates == null) {
                $city = $request->user->city;
            }
            else {
                $user = $request->user;
                $user->update(['city' => $city]);
                $user->save();
            }

            $query = ($city) ?: $coordinates;

            $forecast = $lowm->getWeatherForecast($query, $lang, $units, $days, true);

            $data = [
                'city' => [
                    'id' => $forecast->city->id,
                    'name' => $forecast->city->name,
                    'lat' => $forecast->city->lat,
                    'lon' => $forecast->city->lon,
                    'country' => $forecast->city->country,
                    'population' => $forecast->city->population
                ],
                'sun' => [
                    'rise' => [
                        'date' => $forecast->sun->rise->setTimezone($tz)->format('Y-m-d H:i:s'),
                        'timestamp' => $forecast->sun->rise->setTimezone($tz)->getTimestamp()
                    ],
                    'set' => [
                        'date' => $forecast->sun->set->setTimezone($tz)->format('Y-m-d H:i:s'),
                        'timestamp' => $forecast->sun->set->setTimezone($tz)->getTimestamp()
                    ]
                ],
                'lastUpdate' => [
                    'date' => $forecast->lastUpdate->setTimezone($tz)->format('Y-m-d H:i:s'),
                    'timestamp' => $forecast->lastUpdate->setTimezone($tz)->getTimestamp()
                ]
            ];

            foreach ($forecast as $obj) {

                $day = $obj->time->day->setTimezone($tz);
                $from = $obj->time->from->setTimezone($tz);
                $to = $obj->time->to->setTimezone($tz);

                $temp = [

                    'time' => [
                        'from' => [
                            'date' => $from->format('Y-m-d H:i:s'),
                            'timestamp' => $from->getTimestamp()
                        ],
                        'to' => [
                            'date' => $to->format('Y-m-d H:i:s'),
                            'timestamp' => $to->getTimestamp()
                        ],
                        'day' => [
                            'date' => $day->format('Y-m-d H:i:s'),
                            'timestamp' => $day->getTimestamp()
                        ],
                    ],

                    'lastUpdate' => [
                        'date' => $obj->lastUpdate->setTimezone($tz)->format('Y-m-d H:i:s'),
                        'timestamp' => $obj->lastUpdate->setTimezone($tz)->getTimestamp()
                    ]
                ];

                if (isset($last_day)) {

                    if($day->format('Y-m-d H:i:s') == $last_day){

                        // ISO-8601 numeric representation of the day of the week
                        // 1 (for Monday) through 7 (for Sunday)
                        $day_key = $day->format('N');

                        // The OWM API returns 3 hour forecast data, it means for each day you requested you'll
                        // get weather data each 3 hours in this order:
                        // 06:00 - 09:00, 09:00 - 12:00, 12:00 - 15:00, 15:00 - 18:00, 18:00 - 21:00, 21:00 - 00:00.
                        // So to maintain a well-ordered info I built a key depending on the hours range
                        // (ie: ['06-09'] => [ ... ]).
                        $time_key = $from->format('H').'-'.$to->format('H');

                        $data['days'][$day_key][$time_key] = array_merge($temp, $this->parseData($obj));
                    }
                }

                $last_day = $day->format('Y-m-d H:i:s');
            }

        return $this->responseSuccess($data);
        } 
        catch (\Exception $exception) {
            return $this->responseErrorException($exception->getMessage(), $exception->getCode(), 500);
        }
    }

    /**
     * Helper function to parse data.
     *
     * @param $obj
     * @return array
     */
    private function parseData($obj)
    {
        $data = [
            'temperature' => [
                'now' => [
                    'value' => $obj->temperature->now->getValue(),
                    'unit' => $obj->temperature->now->getUnit()
                ],
                'min' => [
                    'value' => $obj->temperature->min->getValue(),
                    'unit' => $obj->temperature->min->getUnit()
                ],
                'max' => [
                    'value' => $obj->temperature->max->getValue(),
                    'unit' => $obj->temperature->max->getUnit()
                ]
            ],
            'humidity' => [
                'value' => $obj->humidity->getValue(),
                'unit' => $obj->humidity->getUnit()
            ],
            'pressure' => [
                'value' => $obj->pressure->getValue(),
                'unit' => $obj->pressure->getUnit()
            ],
            'wind' => [
                'speed' => [
                    'value' => $obj->wind->speed->getValue(),
                    'unit' => $obj->wind->speed->getUnit(),
                    'description' => $obj->wind->speed->getDescription(),
                    'description_slug' => str_slug($obj->wind->speed->getDescription())
                ],
                'direction' => [
                    'value' => $obj->wind->direction->getValue(),
                    'unit' => $obj->wind->direction->getUnit(),
                    'description' => $obj->wind->direction->getDescription(),
                    'description_slug' => str_slug($obj->wind->direction->getDescription())
                ]
            ],
            'clouds' => [
                'value' => $obj->clouds->getValue(),
                'unit' => $obj->clouds->getUnit(),
                'description' => $obj->clouds->getDescription(),
                'description_slug' => str_slug($obj->clouds->getDescription())
            ],
            'precipitation' => [
                'value' => $obj->precipitation->getValue(),
                'unit' => $obj->precipitation->getUnit(),
                'description' => $obj->precipitation->getDescription(),
                'description_slug' => str_slug($obj->precipitation->getDescription())
            ],
            'weather' => [
                'id' => $obj->weather->id,
                'description' => $obj->weather->description,
                'description_slug' => str_slug($obj->weather->description),
                'icon' => $obj->weather->icon
            ],
        ];

        return $data;
    }

}
