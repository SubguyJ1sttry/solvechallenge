package com.tf4beta.ems.main.controllers;

import java.util.List;
import com.tf4beta.ems.main.entity.User;
import com.tf4beta.ems.main.service.UserService;
import com.tf4beta.ems.main.repository.UserRepository;
import org.springframework.security.core.Authentication;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.ui.Model;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import com.tf4beta.ems.main.entity.Employee;
import com.tf4beta.ems.main.service.EmployeeService;

@Controller
@RequestMapping("/employees")
public class EmployeeController {

	private EmployeeService employeeService;
	private UserService userService;

	private boolean isAdmin() {
		Authentication auth = SecurityContextHolder.getContext().getAuthentication();
		for (GrantedAuthority authority : auth.getAuthorities()) {
			System.out.println(auth.getAuthorities());
			if (authority.getAuthority().equals("ROLE_ADMINISTRATOR")) {
				return true;
			}
		}
		return false;
	}

	@Autowired
	public EmployeeController(EmployeeService employeeService, UserService userService) {
		this.employeeService = employeeService;
		this.userService = userService;
	}

	@GetMapping("/index")
	public String employeeIndex() {

		return "employees/employee-index.html";
	}

	@GetMapping("/list")
	public String listEmployees(Model theModel) {

		List<Employee> theEmployees = employeeService.findAll();

		theModel.addAttribute("employees", theEmployees);

		return "employees/list-employees";
	}

	@RequestMapping("/showEmployeeDetails")
	public String viewEmploye(@RequestParam("employeeId") int theId, Model model) {
		
		Employee employee = employeeService.findById(theId);
		
		model.addAttribute("employee", employee);
		
		return "employees/employee-view";
	}

	@GetMapping("/searchByName")
	public String searchByName(@RequestParam("searchName") String searchName, Model model) {

		List<Employee> theEmployees = employeeService.searchByName(searchName);
		
		if(theEmployees.isEmpty()) {
			model.addAttribute("searchWarning","Sorry!! Search not found."); 
		}

		model.addAttribute("employees", theEmployees);

		return "employees/list-employees";
	}

	@GetMapping("/showFormForAdd")
	public String showFormForAdd(Model theModel) {
		if (!isAdmin()) {
			return "error/403"; // Trang 403 không có quyền truy cập
		}
		Employee employee = new Employee();
		
		theModel.addAttribute("employee", employee);

		return "employees/employee-form";
	}

	@GetMapping("/showFormForUpdate")
	public String showFormForUpdate(@RequestParam("employeeId") int theId, Model theModel) {
		if (!isAdmin()) {
			return "error/403"; // Trang 403 không có quyền truy cập
		}
		Employee employee = employeeService.findById(theId);

		theModel.addAttribute("employee", employee);

		return "employees/employee-updateform";
	}

	@PostMapping("/save")
	public String saveEmployee(@ModelAttribute("employee") Employee employee) {
		
		employeeService.save(employee);
		
		return "redirect:/employees/list";
	}

	@PostMapping("/update")
	public String updateEmployee(@ModelAttribute("employee") Employee employee) {
		if (!isAdmin()) {
			return "error/403"; // Trang 403 không có quyền truy cập
		}
		employeeService.update(employee);
		
		return "redirect:/employees/list";
	}

	@GetMapping("/delete")
	public String delete(@RequestParam("employeeId") int theId) {
		if (!isAdmin()) {
			return "error/403"; // Trang 403 không có quyền truy cập
		}
		employeeService.deleteById(theId);
		
		return "redirect:/employees/list";
	}
	@GetMapping("/profile")
	public String viewProfile(Model model, Authentication authentication) {
		if (!isAdmin()) {
			return "error/403"; // Trang 403 không có quyền truy cập
		}
		String username = authentication.getName();
		User user = userService.findByUsername(username);

		model.addAttribute("user", user);
		return "employees/profile";
	}

	@PostMapping("/profile")
	public String updateUserProfile(@ModelAttribute("user") User user, Authentication authentication) {
		if (!isAdmin()) {
			return "error/403"; // Trang 403 không có quyền truy cập
		}
		String currentUsername = authentication.getName();
		User existingUser = userService.findByUsername(currentUsername);

		// Update user fields
		existingUser.setName(user.getName());
		existingUser.setEmail(user.getEmail());
		existingUser.setPhone(user.getPhone());
		existingUser.setAddress(user.getAddress());
		existingUser.setCity(user.getCity());
		existingUser.setCountry(user.getCountry());
		existingUser.setZip(user.getZip());
		existingUser.setDefaultAddress(user.getDefaultAddress());

		userService.save(existingUser);
		return "redirect:/employees/profile";
	}
	@GetMapping("/feedback")
	public String showFeedbackForm(Model model, Authentication authentication) {
		String username = authentication.getName();
		User user = userService.findByUsername(username);

		model.addAttribute("user", user);
		return "employees/feedback";
	}

	@PostMapping("/feedback")
	public String submitFeedback(Authentication authentication, @RequestParam("feedback") String feedback, Model model) {
		String username = authentication.getName();
		User user = userService.findByUsername(username);


		model.addAttribute("successMessage", "Thông tin của bạn đã được gửi!");

		return "redirect:/employees/feedback?success";
	}
}