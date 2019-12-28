import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NutrientsManagementComponent } from './nutrients-management.component';

describe('NutrientsManagementComponent', () => {
  let component: NutrientsManagementComponent;
  let fixture: ComponentFixture<NutrientsManagementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NutrientsManagementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NutrientsManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
