<?php 
 
namespace App\Http\Requests; 
 
use App\Enums\RoleEnum; 
use App\Enums\StateEnum; 
use App\Rules\CustumPasswordRule; 
use App\Rules\TelephoneRule; 
use App\Traits\RestResponseTrait; 
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Foundation\Http\FormRequest; 
use Illuminate\Http\Exceptions\HttpResponseException; 
 
class StoreClientRequest extends FormRequest 
{ 
    use RestResponseTrait; 

    public function authorize(): bool 
    { 
        return true; 
    } 

    // public function rules(): array 
    // { 
    //     $rules = [ 
    //         'surname' => ['required', 'string', 'max:255','unique:clients,surname'], 
    //         'address' => ['string', 'max:255'], 
    //         'telephone' => [
    //             'required',
    //             'unique:clients,telephone',
    //             'regex:/^(77|76|75|78)\d{7}$/', // Regex pour valider le préfixe et la longueur du numéro
    //             new TelephoneRule()
    //         ],
            
    //         'user' => ['sometimes','array'], 
    //         'user.nom' => ['required_with:user','string'], 
    //         'user.prenom' => ['required_with:user','string'], 
    //         'user.login' => ['required_with:user','string','unique:users,login'],
    //         'user.role_id' => 'required|exists:roles,id',
    //         'user.password' => ['required_with:user', new CustumPasswordRule()],
    //         'user.photo' => ['required_with:user','image','mimes:jpeg,jpg,png,gif'],
    //     ]; 
 
    //     return $rules; 
    // } 

    public function rules(): array
{
    $rules = [
        'surname' => ['required', 'string', 'max:255', 'unique:clients,surname'],
        'address' => ['string', 'max:255'],
        'telephone' => [
            'required',
            'unique:clients,telephone',
            'regex:/^\+221(70|75|76|77|78)\d{7}$/', // Regex pour valider le préfixe et la longueur du numéro
            new TelephoneRule()
        ],
        // Champs utilisateur optionnels
        'user' => ['nullable', 'array'],
        'user.nom' => ['required_with:user', 'string'],
        'user.prenom' => ['required_with:user', 'string'],
        'user.login' => ['required_with:user', 'string', 'unique:users,login'],
        'user.role_id' => ['required_with:user', 'exists:roles,id'],
        'user.password' => ['required_with:user', new CustumPasswordRule()],
        'user.photo' => ['required_with:user', 'image', 'mimes:jpeg,jpg,png,gif'],
    ];

    return $rules;
}

 
    public function messages() 
    { 
        return [ 
            'surname.required' => "Le surnom est obligatoire.", 
            'telephone.regex' => "Le numéro de téléphone doit commencer par +221 70, 77, 76, 75 ou 78 et contenir 9 chiffres après +221.",
        ]; 
    } 
 
    public function failedValidation(Validator $validator)
    { 
        throw new HttpResponseException(
            $this->sendResponse($validator->errors(), StateEnum::ECHEC, 404)
        ); 
    } 
} 
